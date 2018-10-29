<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\GrupoInvestigacion;
use App\Libraries\{ Helper, Form, Permissions };


class AdministradorController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido',
        'numero_documento.unique' => 'Parece que ya existe un usuario con este número de documento',
        'email.unique' => 'Parece que ya existe un usuario con este email',
        'asesor_id.exists' => 'Parece que no existe el asesor'
    ];

    public function create_usuario_asesor() {
        $usuario = \Auth::guard()->user()->usuario;

        if ($usuario->is_administrador()) {
            return view('usuarios.create_asesor', compact(['usuario']));
        }
        abort(404, 'Pagina solicitada no existe');
    }

    public function store_usuario_asesor(Request $request) {
        $usuario_request = \Auth::guard()->user()->usuario;
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'password' => 'required',
            'apellidos' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required|unique:usuarios',
            'email' => 'required|string|email|max:255|unique:users'
        ], $this->messages);

        if ($usuario_request->is_administrador()) {
            $usuario = null;
            Helper::atomic_transaction(function () use (&$usuario, $validated_data) {
                $user = Usuario::create_user($validated_data);
                $usuario = Usuario::create(array_merge($validated_data, [
                    'user_id' => $user->id,
                    'tipo_usuario' => Usuario::$ASESOR
                ]));
            });

            $remote_asesor = DB::connection('remote')
                ->table('est_asesor')
                ->where('identificacion', 'ILIKE', '%' . $validated_data['numero_documento'] . '%')
                ->first();

            if ($remote_asesor) {
                $proyectos = DB::connection('remote')
                    ->table('pro_proyectosede')
                    ->join('est_asesorcoordinador', 'pro_proyectosede.codasesorcoordinador', '=', 'est_asesorcoordinador.codigo')
                    ->where('est_asesorcoordinador.codasesor', $remote_asesor->codigo)
                    ->select('pro_proyectosede.codigo as codigo_proyecto');

                if ($proyectos->exists()) {
                    $proyectos->get()->map(function ($proyecto) use ($usuario) {
                        // $proyecto->codigo_proyecto
                        $grupo_investigacion = GrupoInvestigacion::where('tipo', GrupoInvestigacion::$INVESTIGACION)
                            ->where('codigo', $proyecto->codigo_proyecto)
                            ->select('id');

                        if ($grupo_investigacion->exists()) {
                            $grupo_investigacion->get()->map(function ($grupo) use ($usuario) {
                                DB::table('coordinadores_grupos_investigacion')->insert([
                                    'linea_investigacion_id' => $grupo->id,
                                    'usuario_id' => $usuario->id
                                ]);
                            });
                        }
                    });
                }

                $redes = DB::connection('remote')
                    ->table('rt_redtematicasede')
                    ->join('est_asesorcoordinador', 'rt_redtematicasede.codasesorcoordinador', '=', 'est_asesorcoordinador.codigo')
                    ->where('est_asesorcoordinador.codasesor', $remote_asesor->codigo)
                    ->select('rt_redtematicasede.codigo as codigo_red');

                if ($redes->exists()) {
                    $redes->get()->map(function ($red) use ($usuario) {
                        $redes_tematicas = GrupoInvestigacion::where('tipo', GrupoInvestigacion::$TEMATICA)
                            ->where('codigo', $red->codigo_red)
                            ->select('id');

                        if ($redes_tematicas->exists()) {
                            $redes_tematicas->get()->map(function ($red) use ($usuario) {
                                DB::table('coordinadores_grupos_investigacion')->insert([
                                    'linea_investigacion_id' => $red->id,
                                    'usuario_id' => $usuario->id
                                ]);
                            });
                        }
                    });
                }
            }

            return redirect()->route('admin.create-usuario-asesor')->with('success', 'Se ha creado el usuario asesor con éxito');
        }
        abort(404, 'Pagina solicitada no existe');
    }

    public function get_asesor_data(Request $request) {
        $validated_data = $request->validate([
            'identificacion' => 'required'
        ]);

        $query = DB::connection('remote')
            ->table('est_asesor')
            ->where('identificacion', 'ILIKE', '%' . $validated_data['identificacion'] .'%')
            ->first();

        if ($query) {
            return response()->json([
                'code' => 200,
                'message' => 'User found',
                'object' => $query
            ]);
        }

        return response()->json([
            'code' => 404,
            'message' => 'User not found'
        ]);
    }

    public function asignar_asesor_grupo($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);

        if (\Auth::guard()->user()->is_administrador()) {
            $opciones = [];
            Usuario::where('tipo_usuario', Usuario::$ASESOR)->get()->map(function ($usuario) use (&$opciones) {
                $opciones[$usuario->id] = $usuario->get_full_name();
            });

            $form = new Form(Usuario::class, ['asesor_id'], [
                'asesor_id' => [
                    'type' => 'select',
                    'label' => 'Asesor',
                    'opciones' => $opciones
                ]
            ]);
            return view('admin.asignar_asesor_grupo', compact(['form', 'grupo']));
        }
        abort(404, 'No tienes permisos de estar aquí');
    }

    public function guardar_asesor_grupo(Request $request, $id) {
        $grupo = GrupoInvestigacion::findOrFail($id);

        if (\Auth::guard()->user()->is_administrador()) {
            $validated_data = $request->validate([
                'asesor_id' => 'required|exists:usuarios,id'
            ]);

            $asesor = Usuario::find($validated_data['asesor_id']);

            if (!$asesor->is_asesor()) {
                return back()->withInput();
            }

            $exists = DB::table('coordinadores_grupos_investigacion')
                ->where('usuario_id', $asesor->id)
                ->where('linea_investigacion_id', $grupo->id)
                ->exists();

            if (!$exists) {
                DB::table('coordinadores_grupos_investigacion')->insert([
                    'usuario_id' => $asesor->id,
                    'linea_investigacion_id' => $grupo->id
                ]);
            }

            if ($grupo->tipo == GrupoInvestigacion::$TEMATICA) {
                return redirect()
                    ->route('grupos.show', $grupo->id)
                    ->with('success', 'Se ha añadido el asesor a la red');
            }
            return redirect()
                ->route('grupos.show', $grupo->id)
                ->with('success', 'Se ha añadido el asesor al grupo');
        }
        abort(404, 'No tienes permisos de estar aquí');
    }

    public function reportes_comentarios() {
        if (Permissions::has_perm('administrador')) {
            $usuario = \Auth::guard()->user()->usuario;
            $reportes_ids = \App\Models\ReporteComentarioPost::join('comentarios_posts', 'comentarios_posts.id', '=', 'reportes_comentarios_posts.comentario_id')
                ->whereNotNull('reportes_comentarios_posts.comentario_id')
                ->where('comentarios_posts.estado', \App\Models\ComentarioPost::$ACTIVO)
                ->select('reportes_comentarios_posts.id as id')->get();
            $reportes = \App\Models\ReporteComentarioPost::find($reportes_ids->map(function ($reporte) {
                return $reporte->id;
            })->all());
            return view('admin.reportes', compact(['reportes', 'usuario']));
        }
        abort(404, 'Página no encontrada');
    }

    public function reportes_posts() {
        if (Permissions::has_perm('administrador')) {
            $usuario = \Auth::guard()->user()->usuario;
            $reportes_ids = \App\Models\ReporteComentarioPost::join('posts', 'posts.id', '=', 'reportes_comentarios_posts.post_id')
                ->whereNotNull('reportes_comentarios_posts.post_id')
                ->where('posts.estado', \App\Models\Post::$ACTIVO)
                ->select('reportes_comentarios_posts.id as id')->get();
            $reportes = \App\Models\ReporteComentarioPost::find($reportes_ids->map(function ($reporte) {
                return $reporte->id;
            })->all());
            return view('admin.reportes', compact(['reportes', 'usuario']));
        }
        abort(404, 'Página no encontrada');
    }

    public function inactivar_comentario_post($id) {
        $reporte = \App\Models\ReporteComentarioPost::findOrFail($id);

        if (Permissions::has_perm('administrador')) {
            $is_comentario = $reporte->get_tipo() == \App\Models\ReporteComentarioPost::$COMENTARIO;
            $obj = $is_comentario ?
                $reporte->comentario : $reporte->post;
            $obj->update(['estado' => $is_comentario ?
                \App\Models\ComentarioPost::$INACTIVO : \App\Models\Post::$INACTIVO]);
            return back()->with('success', 'Se ha inactivado el ' . ($is_comentario ? 'comentario' : 'post'));
        }
        abort(404, 'Página no encontrada');
    }

    public function eliminar_reporte($id) {
        $reporte = \App\Models\ReporteComentarioPost::findOrFail($id);

        if (Permissions::has_perm('administrador')) {
            $reporte->delete();
            return back()->with('success', 'Se omite el reporte');
        }
        abort(404, 'Página no encontrada');
    }
}
