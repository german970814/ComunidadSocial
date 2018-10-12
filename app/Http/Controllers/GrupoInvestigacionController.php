<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoInvestigacion;
use App\Libraries\Form;

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
        $usuario = \Auth::guard()->user()->usuario;
        $institucion = \App\Models\Institucion::findOrFail($institucion_id);

        if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
            abort(404, 'El tipo de red temática consultada no existe');
        } elseif (!($usuario->is_administrador() || $usuario->is_institucion())) {
            abort(403, 'No tienes permisos para realizar esta acción');
        } elseif ($usuario->is_institucion() && !($usuario->institucion->id == $institucion->id)) {
            return redirect()->route('grupos.create', [$tipo, $usuario->institucion->id]);
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
        return view('grupos.create', compact(['form', 'title', 'institucion', 'tipo']));
    }

    /**
     * Vista para guardar los grupos de investigación
     */
    public function store(Request $request, $tipo, $institucion_id) {
        $usuario = \Auth::guard()->user()->usuario;
        $institucion = \App\Models\Institucion::findOrFail($institucion_id);

        if (!($tipo == 'tematica' || $tipo == 'investigacion')) {
            abort(404, 'El tipo de red temática consultada no existe');
        } elseif (!($usuario->is_administrador() || $usuario->is_institucion())) {
            abort(403, 'No tienes permisos para realizar esta acción');
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
}