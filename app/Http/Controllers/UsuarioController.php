<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function index() {
        return view('usuarios.index');
    }

    public function create() {
        return view('usuarios.create');
    }

    public function store(Request $request) {
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'password' => 'required',
            'apellidos' => 'required',
            'tipo_usuario' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'email' => 'required|string|email|max:255|unique:users'
        ], $this->messages);

        try {
            DB::beginTransaction();
            $user = Usuario::create_user($validated_data);
            // echo var_dump(array_merge($validated_data, ['user_id' => $user->id]));
            $usuario = Usuario::create(
                array_merge($validated_data, ['user_id' => $user->id])
            );
            DB::commit();

            return redirect(sprintf('/usuario/%s/', $usuario->id));
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id) {
        $usuario = Usuario::find($id);
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function edit($id) {
        $usuario = Usuario::find($id);
        $form = [
            'nombres' => [
                'type' => 'text',
                'label' => 'Nombres',
                'value' => $usuario->nombres
            ],
            'apellidos' => [
                'label' => 'Apellidos',
                'value' => $usuario->apellidos
            ],
            'sexo' => [
                'label' => 'Sexo',
                'value' => $usuario->sexo,
                'type' => 'select',
                'options' => [
                    'M' => 'Masculino',
                    'F' => 'Femenino',
                ]
            ],
            'tipo_documento' => [
                'label' => 'Tipo de documento',
                'value' => $usuario->tipo_documento,
                'type' => 'select',
                'options' => [
                    'CC' => 'Cedula de ciudadanía',
                    'TI' => 'Tarjeta de identidad',
                    'RG' => 'Registro civil',
                    'NES' => 'Número establecido por la secretaría',
                    'NIP' => 'Número de identificación personal',
                    'NUIP' => 'Número único de identificación personal'
                ]
            ],
            'numero_documento' => [
                'type' => 'number',
                'label' => 'Numero documento',
                'value' => $usuario->numero_documento
            ],
            'grupo_etnico' => [
                'label' => 'Grupo étnico',
                'value' => $usuario->grupo_etnico,
                'type' => 'select',
                'options' => [
                    'IN' => 'Indigenas',
                    'AF' => 'Afrocolombianos',
                    'RO' => 'ROM',
                    'NI' => 'Ninguno'
                ]
            ]
        ];

        return view('usuarios.edit', ['usuario' => $usuario, 'form' => $form]);
    }

    public function update(Request $request, $id) {
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
        ], $this->messages);

        try {
            DB::beginTransaction();
            $usuario = Usuario::find($id);
            $usuario->update(array_merge($validated_data));
            $user = $usuario->user->update($validated_data);
            DB::commit();

            return redirect(sprintf('/usuario/%s/edit/', $usuario->id));
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }
}
