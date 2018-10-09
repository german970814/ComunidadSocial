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
        $form = new Form(Institucion::class, [
            'dane', 'nombre', 'codigo', 'director',
            'telefono', 'fax', 'email',
            'departamento', 'municipio_id'
        ]);
        return view('instituciones.create', compact(['form']));
    }

    public function editar() {
        $usuario = \Auth::guard()->user()->usuario;
        if ($usuario->institucion && $usuario->is_institucion()) {
            $institucion = $usuario->institucion;
            $municipio = $institucion->municipio;
            $form = new Form($institucion, [
                'nombre', 'director',
                'telefono', 'fax', 'email',
                'departamento', 'municipio_id'
            ], [
                'departamento' => ['value' => $municipio->departamento->id]
            ]);

            return view('instituciones.edit', compact(['usuario', 'form', 'institucion']));
        }

        abort(404, 'No se puede encontrar lo que buscas');
    }

    public function edit($id) {
        $usuario = \Auth::guard()->user()->usuario;
        if ($usuario->is_administrador()) {
            $institucion = Institucion::findOrFail($id);
            $form = new Form($institucion, [
                'dane', 'nombre', 'codigo', 'director',
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
                'director' => 'required',
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

    public function store(Request $request) {
        $validated_data = $request->validate([
            'dane' => 'required',
            'nombre' => 'required',
            'codigo' => 'required',
            'director' => 'required',
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
                'usuario_id' => $usuario->id
            ]));
        });

        return redirect()
            ->route('institucion.index')
            ->with('success', 'Institución creada exitosamente');
    }
}