<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function store(Request $request) {
        $usuario = \Auth::guard()->user()->usuario;

        $validated_data = $request->validate([
            'mensaje' => 'required',
            'usuario_destino_id' => ''
        ], $this->messages);

        $data = array_merge($validated_data, [
            'autor_id' => $usuario->id
        ]);

        $usuario_destino_id = isset($data['usuario_destino_id']) ? $data['usuario_destino_id'] : null;

        if ($usuario_destino_id) {
            $usuario_destino = \App\Models\Usuario::find($usuario_destino_id)->exists();

            if ($usuario_destino) {
                $post = Post::create([
                    'autor_id' => $data['autor_id'],
                    'mensaje' => $data['mensaje'],
                    'usuario_destino_id' => $usuario_destino_id
                ]);
                if ($usuario_destino_id != $usuario->id) {
                    Notificacion::create_publicacion_post($post, $usuario, ['usuario_id' => $usuario_destino_id]);
                }
                return redirect()->route('usuario.show', $usuario_destino_id);
            }
        }
        Post::create([
            'autor_id' => $data['autor_id'],
            'mensaje' => $data['mensaje'],
            'usuario_destino_id' => $usuario_destino_id
        ]);

        return redirect()->route('usuario.profile');
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
        // $send_notificacion = ($usuario->id != $post->usuario_destino->id) && !($post->is_self_post());
        // echo var_dump($send_notificacion);
        // echo var_dump($usuario->id);
        // echo var_dump($post->usuario_destino->id);

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
}
