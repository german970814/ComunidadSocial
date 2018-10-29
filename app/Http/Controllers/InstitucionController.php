<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institucion;
use App\Libraries\{ Form, Helper };

class InstitucionController extends Controller {

    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function index() {
        $instituciones = Institucion::paginate();
        return view('instituciones.list', ['instituciones' => $instituciones]);
    }

    public function create() {
        if (\Auth::guard()->user()->is_administrador()) {
            $usuario = \Auth::guard()->user()->usuario;
            $form = new Form(Institucion::class, [
                'dane', 'nombre', 'codigo',
                'telefono', 'fax', 'email',
                'departamento', 'municipio_id'
            ]);
            return view('instituciones.create', compact(['form', 'usuario']));
        }
        abort(404, 'Página no encontrada');
    }

    public function editar() {
        $usuario = \Auth::guard()->user()->usuario;
        if ($usuario->institucion && $usuario->is_institucion()) {
            $institucion = $usuario->institucion;
            $municipio = $institucion->municipio;
            $form = new Form($institucion, [
                'nombre', 'telefono', 'fax', 'email',
                'departamento', 'municipio_id'
            ], [
                'departamento' => ['value' => $municipio->departamento->id]
            ]);

            return view('instituciones.edit', compact(['usuario', 'form', 'institucion']));
        }

        abort(404, 'No se puede encontrar lo que buscas');
    }

    public function edit($id) {
        $institucion = Institucion::findOrFail($id);
        $administrador = \Auth::guard()->user()->usuario;
        if ($administrador->is_administrador()) {
            $usuario = $institucion->usuario;
            $form = new Form($institucion, [
                'dane', 'nombre', 'codigo',
                'telefono', 'fax', 'email',
                'departamento', 'municipio_id'
            ]);

            return view('instituciones.edit', compact(['usuario', 'form', 'institucion']));
        }
        return redirect()->route('institucion.editar');
    }

    public function update(Request $request, $id) {  // TODO: actualizar nombre de usuario
        $institucion = Institucion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if ($usuario->is_administrador() || $usuario->id === $institucion->usuario->id) {
            $validated_data = $request->validate([
                'dane' => '',
                'nombre' => 'required',
                'codigo' => '',
                'telefono' => '',
                'fax' => '',
                'email' => '',
                'municipio_id' => 'required|exists:municipios,id',
            ]);

            $institucion->update($validated_data);

            if ($usuario->is_administrador()) {
                return redirect()
                    ->route('institucion.edit', $institucion->id)
                    ->with('success', 'Ha editado la institución exitosamente');
            }
            return redirect()
                ->route('institucion.editar')
                ->with('success', 'Has editado la institución exitosamente');
        }
        return back()->with('error', 'No tiene permisos de realizar esta acción');
    }

    /**
     * @return JsonResponse
     */
    public function solicitud_ingreso_institucion($id) {  // TODO: cancelar ingreso a institución
        $usuario = \Auth::guard()->user()->usuario;
        $institucion = Institucion::findOrFail($id);
        if ($usuario->is_estudiante() || $usuario->is_maestro()) {
            if (!$usuario->institucion_pertenece()) {
                $solicitud = \App\Models\SolicitudInstitucion::where('usuario_id', $usuario->id)
                    ->first();

                if ($solicitud) {
                    \App\Models\Notificacion::where('usuario_id', $solicitud->institucion->usuario_id)
                        ->where('usuario_sender_id', $usuario->id)
                        ->where('tipo', \App\Models\Notificacion::$ingreso_institucion_tipo)
                        ->delete();
                    $solicitud->update(['institucion_id' => $institucion->id]);
                    \App\Models\Notificacion::create_ingreso_institucion($solicitud, [
                        'usuario_id' => $institucion->usuario->id
                    ]);
                } else {
                    $solicitud = \App\Models\SolicitudInstitucion::create([
                        'usuario_id' => $usuario->id,
                        'institucion_id' => $institucion->id,
                        'aceptada' => false
                    ]);
                    \App\Models\Notificacion::create_ingreso_institucion($solicitud, [
                        'usuario_id' => $institucion->usuario->id
                    ]);
                }
                return back()->with('success', 'Solicitud de ingreso envíada');
            }
        }

        return response()->json([
            'code' => 419,
            'message' => 'No tienes permisos para envíar una solicitud'
        ]);
    }

    public function aceptar_solicitud_ingreso_institucion(Request $request, $id_solicitud) {
        $solicitud = \App\Models\SolicitudInstitucion::findOrFail($id_solicitud);
        $usuario = \Auth::guard()->user()->usuario;
        if ($usuario->is_administrador() || $usuario->id == $solicitud->institucion->usuario->id) {
            $solicitud->update(['aceptada' => true]);
            return back()->with('success', 'Haz aceptado la solicitud de ingreso a la institución');
        }
        return back()->with('warning', 'Parece que no tienes permisos de entrar a esta página');
    }

    public function rechazar_solicitud_ingreso_institucion(Request $request, $id_solicitud) {
        $solicitud = \App\Models\SolicitudInstitucion::findOrFail($id_solicitud);
        $usuario = \Auth::guard()->user()->usuario;
        if ($usuario->is_administrador() || $usuario->id == $solicitud->institucion->usuario->id) {
            $solicitud->delete();
            return back()->with('info', 'Haz eliminado la solicitud de ingreso de la institución');
        }
        return back()->with('warning', 'Parece que no tienes permisos de entrar a esta página');
    }

    public function integrantes($id) {
        $institucion = Institucion::findOrFail($id);
        $usuario_request = \Auth::guard()->user()->usuario;
        $usuario = $institucion->usuario;

        return view('instituciones.integrantes', compact(['usuario', 'institucion']));
    }

    public function solicitudes_ingreso_institucion($id) {
        $institucion = Institucion::findOrFail($id);
        $usuario_request = \Auth::guard()->user()->usuario;

        if ($usuario_request->is_administrador() || $usuario_request->id === $institucion->usuario->id) {
            $solicitudes = $institucion->solicitudes;
            $usuario = $institucion->usuario;
            return view('instituciones.solicitudes', compact(['usuario', 'institucion', 'solicitudes']));
        }

        if ($usuario_request->is_institucion() && $usuario_request->institucion) {
            return redirect()
                ->route('institucion.solicitudes-ingreso-institucion', $usuario_request->institucion->id);
        }
        abort(419, 'Parece que no tienes permisos de entrar a esta página');
    }

    public function store(Request $request) {
        if (\Auth::guard()->user()->is_administrador()) {
            $validated_data = $request->validate([
                'dane' => 'required',
                'nombre' => 'required',
                'codigo' => 'required',
                'telefono' => '',
                'fax' => '',
                'email' => '',
                'municipio_id' => 'required|exists:municipios,id',
            ], $this->messages);
            $institucion = null;
    
            Helper::atomic_transaction(function () use ($request, $validated_data, &$institucion) {
                $user = \App\Models\Usuario::create_user([
                    'apellidos' => '',
                    'nombres' => $validated_data['nombre'],
                    'email' => $validated_data['dane'],
                    'username' => $validated_data['dane'],
                    'password' => $validated_data['dane']
                ]);
    
                $usuario = \App\Models\Usuario::create([
                    'sexo' => 'M',
                    'apellidos' => '',
                    'user_id' => $user->id,
                    'tipo_documento' => 'NES',
                    'nombres' => $validated_data['nombre'],
                    'numero_documento' => $validated_data['dane'],
                    'tipo_usuario' => \App\Models\Usuario::$INSTITUCION
                ]);
    
                $institucion = Institucion::create(array_merge($validated_data, [
                    'usuario_id' => $usuario->id, 'director' => '-'
                ]));
            });
    
            return redirect()
                ->route('institucion.index')
                ->with('success', 'Institución creada exitosamente');
        }
        abort(404, 'Página no encontrada');
    }
}