<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\ComentarioPost;
use App\Models\Notificacion;

class PostController extends Controller
{
    private $messages = [
        'required' => 'Este campo es requerido'
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function show(Request $request, $id) {
        $post = Post::findOrFail($id);
        return view('posts.post', ['post' => $post]);
    }

    public function store(Request $request, $tipo) {
        $usuario = \Auth::guard()->user()->usuario;

        if (!($tipo == Post::$post_usuario_tipo || $tipo == Post::$post_grupo_tipo)) {
            return back()->with('error', 'Petición incorrecta');
        }

        if ($tipo == Post::$post_usuario_tipo) {
            $validated_data = $request->validate([
                'mensaje' => 'required',
                'usuario_destino_id' => 'required|exists:usuarios,id',
                'photo' => 'file|image|mimes:jpeg,png,gif,webp|max:2048'
            ], $this->messages);
        } else {
            $validated_data = $request->validate([
                'mensaje' => 'required',
                'grupo_destino_id' => 'required|exists:grupos_investigacion,id',
                'photo' => 'file|image|mimes:jpeg,png,gif,webp|max:2048'
            ], $this->messages);
        }

        $data = array_merge($validated_data, [
            'autor_id' => $usuario->id
        ]);

        if ($tipo == Post::$post_usuario_tipo) {
            $file = isset($validated_data['photo']) ? $validated_data['photo'] : null;
            $post = Post::create([
                'autor_id' => $data['autor_id'],
                'mensaje' => $data['mensaje'],
                'tipo' => Post::$post_usuario_tipo,
                'usuario_destino_id' => $data['usuario_destino_id']
            ]);

            if ($file) {
                $filename = sprintf(
                    'posts/post-%s.%s', $post->id, $file->getClientOriginalExtension()
                );
                Storage::disk('local')->put($filename, File::get($file));
                $post->update(['photo' => $filename]);
            }

            if ($data['usuario_destino_id'] != $usuario->id) {
                Notificacion::create_publicacion_post($post, $usuario, ['usuario_id' => $data['usuario_destino_id']]);
                return redirect()->route('usuario.show', $data['usuario_destino_id']);
            }
            return redirect()->route('usuario.profile');
        }

        $file = isset($validated_data['photo']) ? $validated_data['photo'] : null;
        $grupo = \App\Models\GrupoInvestigacion::find($data['grupo_destino_id']);

        $post = Post::create([
            'autor_id' => $data['autor_id'],
            'mensaje' => $data['mensaje'],
            'tipo' => Post::$post_grupo_tipo,
            'grupo_destino_id' => $grupo->id
        ]);

        if ($file) {
            $filename = sprintf(
                'posts/post-%s.%s', $post->id, $file->getClientOriginalExtension()
            );
            Storage::disk('local')->put($filename, File::get($file));
            $post->update(['photo' => $filename]);
        }

        // Notificación
        // Notificacion::create_publicacion_post($post, $usuario, ['usuario_id' => $grupo->id]);
        return redirect()->route('grupos.show', $grupo->id);
    }

    public function comment(Request $request, $id) {
        $post = Post::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        $validated_data = $request->validate([
            'mensaje' => 'required'
        ]);

        $comentario = ComentarioPost::create([
            'mensaje' => $validated_data['mensaje'],
            'usuario_id' => $usuario->id,
            'post_id' => $post->id,
            'like' => false
        ]);

        if ($usuario->id != $post->usuario_destino->id) {
            Notificacion::create_comentario_post($comentario, ['usuario_id' => $post->usuario_destino->id]);
        }
        if ($usuario->id != $post->autor->id) {
            Notificacion::create_comentario_post($comentario, ['usuario_id' => $post->autor->id]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Comentario creado exitosamente',
            'data' => array_merge($comentario->toArray(), ['usuario' => $comentario->usuario->toArray()])
        ]);
    }

    public function like_post(Request $request, $id) {
        // Para las notificaciones de los likes, si la notificación no se ha leido se elimina
        $post = Post::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;
        $should_notify = !($post->is_self_post() && $usuario->id == $post->autor->id);

        $like = ComentarioPost::where('usuario_id', $usuario->id)
            ->where('post_id', $post->id)
            ->where('mensaje', '%-like-%')
            ->first();

        if (!$like) {
            ComentarioPost::create([
                'mensaje' => '%-like-%',
                'usuario_id' => $usuario->id,
                'post_id' => $post->id,
                'like' => true
            ]);

            if ($should_notify) {
                if ($post->usuario_destino->id != $usuario->id) {
                    Notificacion::create_like_post(
                        $post,
                        $usuario,
                        ['usuario_id' => $post->usuario_destino->id]
                    );
                }
                if (($post->autor->id != $usuario->id) && !$post->is_self_post()) {
                    Notificacion::create_like_post(
                        $post,
                        $usuario,
                        ['usuario_id' => $post->autor->id]
                    );
                }
            }
        } else {
            $like->update(['like' => !$like->like]);
            if (!$like->like) {
                // TODO: Verify
                $exists_for_destino = Notificacion::where('tipo', Notificacion::$like_post_tipo)
                    ->where('usuario_sender_id', $usuario->id)
                    ->where('usuario_id', $post->usuario_destino->id)
                    ->where('leida', false)
                    ->first();
                $exists_for_autor = Notificacion::where('tipo', Notificacion::$like_post_tipo)
                    ->where('usuario_sender_id', $usuario->id)
                    ->where('usuario_id', $post->autor->id)
                    ->where('leida', false)
                    ->first();

                if ($exists_for_destino) {
                    $exists_for_destino->delete();
                }
                if ($exists_for_autor) {
                    $exists_for_autor->delete();
                }
            } else {
                if ($should_notify) {
                    if ($post->usuario_destino->id != $usuario->id) {
                        Notificacion::create_like_post(
                            $post,
                            $usuario,
                            ['usuario_id' => $post->usuario_destino->id]
                        );
                    }
                    if (($post->autor->id != $usuario->id) && !$post->is_self_post()) {
                        Notificacion::create_like_post(
                            $post,
                            $usuario,
                            ['usuario_id' => $post->autor->id]
                        );
                    }
                }
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'Comentario creado exitosamente'
        ]);
    }

    public function post_photo($id) {
        $post = Post::findOrFail($id);

        if (Storage::disk('local')->has($post->photo)) {
            $file = Storage::disk('local')->get($post->photo);
            return new Response($file, 200);
        }
    }
}
