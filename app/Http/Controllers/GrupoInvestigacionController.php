<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoInvestigacion;
use App\Libraries\{ Form, Permissions };

class GrupoInvestigacionController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function show($id) {
        $grupo = \App\Models\GrupoInvestigacion::findOrFail($id);
        return view('grupos.show', compact(['grupo']));
    }

    public function create($tipo, $institucion_id) {
        $usuario_request = \Auth::guard()->user()->usuario;
        $institucion = \App\Models\Institucion::findOrFail($institucion_id);

        if (Permissions::has_perm('crear_grupo', compact(['institucion']))) {
            if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
                abort(404, 'El tipo grupo consultado no existe');
            }

            if ($tipo == 'tematica') {
                $title = 'Crear red temática';
                $opciones_linea_investigacion = \App\Models\LineaInvestigacion::where(
                    'tipo', \App\Models\LineaInvestigacion::$TEMATICA
                )->get();
            } else {
                $title = 'Crear grupo de investigación';
                $opciones_linea_investigacion = \App\Models\LineaInvestigacion::where(
                    'tipo', \App\Models\LineaInvestigacion::$INVESTIGACION
                )->get();
            }

            $opciones = [];
            $opciones_linea_investigacion->map(function ($opcion) use (&$opciones) {
                $opciones[$opcion->id] = $opcion->get_nombre();
                return null;
            });

            $usuario = $institucion->usuario;
            $form = new Form(GrupoInvestigacion::class, [
                'nombre', 'descripcion',
                'linea_investigacion_id'
            ], [
                'linea_investigacion_id' => [
                    'type' => 'select',
                    'opciones' => $opciones,
                    'label' => 'Linea investigación',
                    'param' => 'nombre'
                ]
            ]);
            return view('grupos.create', compact(['form', 'title', 'institucion', 'tipo', 'usuario']));
        } else if ($usuario_request->is_institucion($institucion)) {
            return redirect()->route('grupos.create', [$tipo, $usuario_request->institucion->id]);
        }
        abort(404, 'Página no econtrada');
    }

    /**
     * Vista para guardar los grupos de investigación
     */
    public function store(Request $request, $tipo, $institucion_id) {
        $usuario = \Auth::guard()->user()->usuario;
        $institucion = \App\Models\Institucion::findOrFail($institucion_id);

        if (Permissions::has_perm('crear_grupo', compact(['institucion']))) {
            if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
                abort(404, 'El tipo de grupo consultado no existe');
            }

            $validated_data = $request->validate([
                'descripcion' => '',
                'nombre' => 'required',
                'linea_investigacion_id' => 'required|exists:lineas_investigacion,id'
            ], $this->messages);

            GrupoInvestigacion::create(array_merge($validated_data, [
                'institucion_id' => $institucion_id,
                'tipo' => $tipo == 'tematica' ? GrupoInvestigacion::$TEMATICA : GrupoInvestigacion::$INVESTIGACION
            ]));

            return redirect()
                ->route('grupos.grupos-investigacion-institucion', [$tipo, $institucion->usuario->id])
                ->with('success', $tipo == 'tematica' ?
                    'Se ha creacdo la red temática exitosamente' :
                    'Se ha creado el grupo de investigación exitosamente'
                );
        }
        abort(404, 'Página no existe');
    }

    /**
     * Vista para listar los grupos de investigación de un usuario
     * maestro o estudiante, de acuerdo al tipo, consulta grupos de 
     * investigación o redes temáticas
     */
    public function grupos_investigacion_usuario($tipo, $usuario_id) {
        $usuario = \App\Models\Usuario::findOrFail($usuario_id);
        $usuario_request = \Auth::guard()->user();

        if ($usuario->is_maestro() || $usuario->is_estudiante() || $usuario_request->is_administrador()) {
            if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
                abort(404, 'El tipo de red temática consultada no existe');
            }
    
            if ($tipo == 'tematica') {
                $grupos = $usuario->get_redes_tematicas_pertenece();
                $title = 'Redes temáticas a las que pertenece';
            } else {
                $grupos = $usuario->get_grupos_investigacion_pertenece();
                $title = 'Grupos de investigación a los que pertenece';
            }
            return view('grupos.grupo_investigacion_usuario', compact(['grupos', 'usuario', 'title']));
        }
        abort(403, 'No tienes permisos de estar aquí');
    }

    /**
     * Vista para listar los grupos de investigación de un usuario
     * institución, de acuerdo al tipo, consulta grupos de investigación
     * asociados a el, o redes temáticas asociadas a el
     */
    public function grupos_investigacion_institucion($tipo, $usuario_id) {
        $usuario = \App\Models\Usuario::findOrFail($usuario_id);
        $usuario_request = \Auth::guard()->user();

        if ($usuario->is_institucion() || $usuario_request->is_administrador()) {
            if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
                abort(404, 'El tipo de red temática consultada no existe');
            }

            if ($tipo == 'tematica') {
                $grupos = $usuario->institucion->get_redes_tematicas();
                $title = 'Redes temáticas de la institución';
            } else {
                $grupos = $usuario->institucion->get_grupos_investigacion();
                $title = 'Grupos de investigación de la institución';
            }
            return view('grupos.grupo_investigacion_usuario', compact(['grupos', 'usuario', 'title', 'tipo']));
        }
        abort(403, 'No tienes permisos de estar aquí');
    }

    /**
     * Vista para listar los integrantes de un grupo de investigación
     */
    public function integrantes($id) {
        $grupo = GrupoInvestigacion::find($id);
        return view('grupos.integrantes', compact(['grupo']));
    }

    /**
     * Vista para listar las solicitudes de ingreso que tiene un grupo.
     * 
     * Solo pueden ser vistas por administradores y asesores
     */
    public function solicitudes_ingreso($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user();

        if ($usuario->is_administrador() || $usuario->is_asesor()) {
            return view('grupos.solicitudes', compact(['grupo']));
        }
        abort(419, 'No tienes permisos de estar aquí');
    }

    /**
     * Vista para crear y eliminar solicitudes de ingreso a grupos
     */
    public function solicitar_unirse_grupo($id) {
        $usuario = \Auth::guard()->user()->usuario;
        $grupo = GrupoInvestigacion::findOrFail($id);

        if (($usuario->is_maestro() || $usuario->is_estudiante()) && $usuario->puede_unirse_grupo($grupo)) {
            $solicitud = \App\Models\SolicitudGrupoInvestigacion::where('usuario_id', $usuario->id)
                ->where('grupo_investigacion_id', $grupo->id)
                ->where('aceptada', false)
                ->first();

            if ($solicitud) {
                $solicitud->delete();
                return back()->with('info', $grupo->tipo == GrupoInvestigacion::$TEMATICA ?
                    'Se ha cancelado la solicitud a unirse a la red temática' :
                    'Se ha cancelado la solicitud a unirse al grupo de investigación'
                );
            }
            \App\Models\SolicitudGrupoInvestigacion::create([
                'grupo_investigacion_id' => $grupo->id,
                'usuario_id' => $usuario->id,
                'aceptada' => false
            ]);
            
            return back()->with('success', $grupo->tipo == GrupoInvestigacion::$TEMATICA ?
                'Solicitud de ingreso a red temática envíada' :
                'Solicitud de ingreso a grupo de investigación envíada'
            );
        }
    
        return back()->with('error', 'No se puede procesar la solicitud');
    }

    /**
     * Vista para aceptar las solicitudes de los usuarios a las instituciones
     */
    public function aceptar_solicitud($id) {
        $usuario = \Auth::guard()->user()->usuario;
        $solicitud = \App\Models\SolicitudGrupoInvestigacion::findOrFail($id);

        if ($usuario->is_administrador() || $usuario->is_asesor()) {
            $solicitud->update(['aceptada' => true]);
            return back()->with('success', 'Se ha aceptado la solicitud');
        }
        return back()->with('error', 'No tienes permisos de realizar esta acción');
    }

    /**
     * Vista para aceptar las solicitudes de los usuarios a las instituciones
     */
    public function rechazar_solicitud($id) {
        $usuario = \Auth::guard()->user()->usuario;
        $solicitud = \App\Models\SolicitudGrupoInvestigacion::findOrFail($id);

        if ($usuario->is_administrador() || $usuario->is_asesor()) {
            $solicitud->delete();
            return back()->with('success', 'Se ha rechazado la solicitud');
        }
        return back()->with('error', 'No tienes permisos de realizar esta acción');
    }

    /**
     * Vista para crear foros
     */
    public function crear_foro($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('crear_foro', ['grupo' => $grupo])) {
            $form = new Form(\App\Models\ForoGrupo::class, ['tema']);
            return view('grupos.crear_foro', compact(['form', 'grupo', 'usuario']));
        }
        abort(404, 'Página no encontrada');
    }

    public function guardar_foro(Request $request, $id) {
        $grupo = GrupoInvestigacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('crear_foro', ['grupo' => $grupo])) {
            $validated_data = $request->validate([
                'tema' => 'required'
            ], $this->messages);

            $foro = \App\Models\ForoGrupo::create([
                'usuario_id' => $usuario->id,
                'tema' => $validated_data['tema'],
                'grupo_investigacion_id' => $grupo->id,
            ]);

            return redirect()
                ->route('grupos.ver-foro', $foro->id)
                ->with('success', 'Has creado un nuevo tema en el foro');
        }
        abort(404, 'Página no encontrada');
    }

    public function ver_foros($id) {
        $grupo = GrupoInvestigacion::findOrFail($id);

        if (Permissions::has_perm('ver_foros', ['grupo' => $grupo])) {
            return view('grupos.foros', compact(['grupo']));
        }
        abort(404, 'Página no encontrada');
    }

    public function ver_foro($id) {
        $foro = \App\Models\ForoGrupo::findOrFail($id);
        
        if (Permissions::has_perm('ver_foro', ['foro' => $foro])) {
            $grupo = $foro->grupo;
            return view('grupos.foro', compact(['foro', 'grupo']));
        }
        abort(404, 'Página no encontrada');
    }

    public function editar_foro($id) {
        $foro = \App\Models\ForoGrupo::findOrFail($id);

        if (Permissions::has_perm('editar_foro', ['foro' => $foro])) {
            $grupo = $foro->grupo;
            $form = new Form($foro, ['tema']);
            return view('grupos.crear_foro', compact(['form', 'grupo', 'foro']));
        }
        abort(404, 'Página no encontrada');
    }

    public function actualizar_foro(Request $request, $id) {
        $foro = \App\Models\ForoGrupo::findOrFail($id);

        if (Permissions::has_perm('editar_foro', ['foro' => $foro])) {
            $validated_data = $request->validate([
                'tema' => 'required'
            ], $this->messages);

            $foro->update([
                'tema' => $validated_data['tema']
            ]);

            return redirect()->route('grupos.ver-foro', $foro->id)
                ->with('success', 'Se ha editado el foro');
        }
        abort(404, 'Página no disponible');
    }

    public function guardar_respuesta_foro(Request $request, $id) {
        $foro = \App\Models\ForoGrupo::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if (Permissions::has_perm('participar_foro', ['foro' => $foro])) {
            $validated_data = $request->validate([
                'descripcion' => 'required'
            ], $this->messages);

            \App\Models\RespuestaForoGrupo::create([
                'foro_id' => $foro->id,
                'usuario_id' => $usuario->id,
                'descripcion' => $validated_data['descripcion'],
            ]);

            return back()
                ->with('success', 'Ha agregado una respuesta al foro');
        }
        abort(404, 'Página no encontrada');
    }
}