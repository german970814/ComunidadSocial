<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Usuario;
use App\Libraries\Form;

class UsuarioController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido',
        'numero_documento.unique' => 'Parece que ya existe un usuario con este número de documento',
        'email.unique' => 'Parece que ya existe un usuario con este email'
    ];

    public function __construct()
    {
        $this->middleware('guest', ['only' => ['create', 'store', 'get_remote_usuario_data']]);
        $this->middleware('auth', ['except' => ['create', 'store', 'get_remote_usuario_data', 'get_user_profile_photo']]);
    }

    public function index() {
        $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function profile() {
        $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    public function detail($id) {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.informacion', ['usuario' => $usuario]);
    }

    public function create() {
        return view('usuarios.create');
    }

    /**
     * Método para guardar usuarios, este método guarda en la tabla
     * de usuarios y en la tabla de users de laravel
     */
    public function store(Request $request) {
        $validated_data = $request->validate([
            'sexo' => 'required',
            'nombres' => 'required',
            'password' => 'required',
            'apellidos' => 'required',
            'tipo_usuario' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required|unique:usuarios',
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
            \Auth::login($user);

            if ($validated_data['tipo_usuario'] == Usuario::$MAESTRO) {
                // $table = 'ins_docente';
                // DB::connection('remote')
                // ->table('ins_estumatricula');
            } else {
                $usuario_remoto = DB::connection('remote')
                    ->table('ins_estudiante')
                    ->where('identificacion', 'ILIKE', '%' . $validated_data['numero_documento'] . '%')
                    ->first();

                if ($usuario_remoto) {
                    $matricula = DB::connection('remote')
                        ->table('ins_estumatricula')
                        ->where('codestudiante', $usuario_remoto->codigo)
                        ->where('codanio', 2)->first();

                    if (!$matricula) {
                        $matricula = DB::connection('remote')
                        ->table('ins_estumatricula')
                        ->where('codestudiante', $usuario_remoto->codigo)
                        ->where('codanio', 1)->first();
                    }

                    if ($matricula) {
                        $institucion = \App\Models\Institucion::where(
                            'codigo', $matricula->codsede
                        )->first();

                        \App\Models\SolicitudInstitucion::create([
                            'usuario_id' => $usuario->id,
                            'institucion_id' => $institucion->id,
                            'aceptada' => true
                        ]);
                    }
                }
            }

            return redirect()
                ->route('usuario.show', $usuario->id)
                ->with('success', 'Bienvenido, gracias por registrarte');
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Método para mostrar la información de un usuario
     */
    public function show($id) {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.detail', ['usuario' => $usuario]);
    }

    /**
     * Método para mostrar el formulario de edicion de un usuario
     * 
     * @get
     */
    public function edit($id) {
        $usuario = Usuario::findOrFail($id);
        $form_fields = ['nombres', 'apellidos', 'sexo', 'tipo_documento', 'numero_documento', 'grupo_etnico'];
        // $form = new Form('\App\Models\Usuario', $form);
        $form = new Form($usuario, $form_fields);
        return view('usuarios.edit', ['usuario' => $usuario, 'form' => $form]);
    }

    /**
     * Método para actualizar la información de un usuario
     * 
     * @post
     */
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

            return redirect()
                ->route('usuario.edit', $usuario->id)
                ->with('success', 'Se ha editado la información');
        } catch(\PDOException $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Método para cambiar la foto de perfil de un usuario
     */
    public function change_profile_photo(Request $request) {
        $validation = $request->validate([
            'photo' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048'
        ]);

        $usuario = \Auth::guard()->user()->usuario;
        $file = $validation['photo'];
        $filename = sprintf(
            'profiles/usuario-%s.%s', $usuario->id, $file->getClientOriginalExtension()
        );

        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
            $usuario->update(['profile_photo' => $filename]);
        }

        // return redirect()->route('usuario.edit', $usuario->id);
        return back()->with('success', 'Foto de perfil modificada');;
    }

    /**
     * Método para servir la foto de perfil de un usuario
     */
    public function get_user_profile_photo($id) {
        $usuario = Usuario::findOrFail($id);

        if (Storage::disk('local')->has($usuario->profile_photo)) {
            $file = Storage::disk('local')->get($usuario->profile_photo);
            return new Response($file, 200);
        }
    }

    /**
     * Método para listar la vista de amigos de un usuario
     */
    public function amigos($id=null) {
        if ($id) {
            $usuario = Usuario::find($id);
        } else {
            $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        }
        return view('usuarios.amigos', ['usuario' => $usuario]);
    }

    /**
     * Método para buscar amigos de un usuario
     */
    public function buscar_usuarios(Request $request) {
        $usuario = \Auth::guard()->user()->usuario;
        $validated_data = $request->q;

        $usuarios = Usuario::where('nombres', 'ILIKE', '%' . $validated_data . '%')->get()->all();
        return view('usuarios.buscar', ['usuario' => $usuario, 'usuarios' => $usuarios]);
    }

    /**
     * Método para obtener la lista de estudiantes y maestros con la
     * base de datos remota que se provee
     * 
     * @return JsonResponse
     */
    public function get_remote_usuario_data(Request $request) {
        $validated_data = $request->validate([
            'tipo_usuario' => 'required',
            'identificacion' => 'required'
        ]);

        if ($validated_data['tipo_usuario'] == Usuario::$MAESTRO) {
            $table = 'ins_docente';
        } else {
            $table = 'ins_estudiante';
        }

        $query = DB::connection('remote')
            ->table($table)
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
