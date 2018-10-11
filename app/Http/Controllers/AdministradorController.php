<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\GrupoInvestigacion;
use App\Libraries\Helper;


class AdministradorController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido',
        'numero_documento.unique' => 'Parece que ya existe un usuario con este número de documento',
        'email.unique' => 'Parece que ya existe un usuario con este email'
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

            return redirect()->route('admin.create-usuario-asesor')->with('success', 'Se ha creado el usuario acesor con éxito');
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
}
