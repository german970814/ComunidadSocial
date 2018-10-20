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
            $usuario = Usuario::create(
                array_merge($validated_data, ['user_id' => $user->id])
            );
            DB::commit();
            \Auth::login($user);

            if ($validated_data['tipo_usuario'] == Usuario::$MAESTRO) {
                $docente_remoto = DB::connection('remote')
                    ->table('ins_docente')
                    ->where('identificacion', 'ILIKE', '%' . $validated_data['numero_documento'] . '%')
                    ->first();

                if ($docente_remoto) {
                    $matricula = DB::connection('remote')
                        ->table('ins_gradodocente')
                        ->where('identificacion', $docente_remoto->identificacion)
                        ->where('codanio', 2)->first();

                    if (!$matricula) {
                        $matricula = DB::connection('remote')
                            ->table('ins_gradodocente')
                            ->where('identificacion', $docente_remoto->identificacion)
                            ->where('codanio', 1)->first();
                    }

                    if ($matricula) {
                        $institucion = \App\Models\Institucion::where(
                            'codigo', $matricula->codsede
                        )->first();

                        if ($institucion) \App\Models\SolicitudInstitucion::create([
                            'usuario_id' => $usuario->id,
                            'institucion_id' => $institucion->id,
                            'aceptada' => true
                        ]);
                    }
                }
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

                        if ($institucion) \App\Models\SolicitudInstitucion::create([
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
        if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->usuario->id == $id) {
            $form_fields = ['nombres', 'apellidos', 'sexo', 'tipo_documento', 'numero_documento', 'grupo_etnico'];
            // $form = new Form('\App\Models\Usuario', $form);
            $form = new Form($usuario, $form_fields);
            return view('usuarios.edit', ['usuario' => $usuario, 'form' => $form]);
        }
        abort(404, 'La página que solicita no existe');
    }

    /**
     * Método para actualizar la información de un usuario
     * 
     * @post
     */
    public function update(Request $request, $id) {
        $usuario = Usuario::findOrFail($id);

        if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->usuario->id == $id) {
            $validated_data = $request->validate([
                'sexo' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'tipo_documento' => 'required',
                'numero_documento' => 'required',
            ], $this->messages);
    
            try {
                DB::beginTransaction();
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
        abort(404, 'La página que solicita no existe');
    }

    /**
     * Método para cambiar la foto de perfil de un usuario
     */
    public function change_profile_photo(Request $request) {
        $usuario = \Auth::guard()->user()->usuario;

        $validation = $request->validate([
            'photo' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048'
        ]);

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
            $usuario = Usuario::findOrFail($id);
        } else {
            $usuario = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        }
        return view('usuarios.amigos', ['usuario' => $usuario]);
    }

    public function solicitudes_amistad_usuario($id=null) {
        $usuario_request = \Auth::guard()->user()->usuario;
        if ($id && $usuario_request->is_administrador()) {
            $usuario = Usuario::findOrFail($id);
        } elseif ($id) {
            return redirect()->route('usuario.solicitudes-amistad');
        } elseif ($usuario_request->is_estudiante() || $usuario_request->is_maestro()) {
            $usuario = $usuario_request;
        }
        return view('usuarios.solicitudes_amistad', compact(['usuario']));
    }

    /**
     * Método para buscar amigos de un usuario
     */
    public function buscar_usuarios(Request $request) {
        $usuario = \Auth::guard()->user()->usuario;
        $validated_data = $request->q;

        $maestros = Usuario::where('nombres', 'ILIKE', '%' . $validated_data . '%')
            ->where('tipo_usuario', Usuario::$MAESTRO)->get()->all();
        $estudiantes = Usuario::where('nombres', 'ILIKE', '%' . $validated_data . '%')
            ->where('tipo_usuario', Usuario::$ESTUDIANTE)->get()->all();
        $instituciones = Usuario::where('nombres', 'ILIKE', '%' . $validated_data . '%')
            ->where('tipo_usuario', Usuario::$INSTITUCION)->get()->all();
        $usuarios = [
            'maestros' => [
                'title' => 'Maestros',
                'data' => $maestros
            ],
            'estudiantes' => [
                'title' => 'Estudiantes',
                'data' => $estudiantes
            ],
            'instituciones' => [
                'title' => 'Instituciones',
                'data' => $instituciones
            ]
        ];
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

    /**
     * Vista para ver los mensajes de los usuarios
     */
    public function mensajes_usuario() {
        $usuario = \Auth::guard()->user()->usuario;
        $show_chat = false;
        return view('usuarios.mensajes', compact(['usuario', 'show_chat']));
    }
}
