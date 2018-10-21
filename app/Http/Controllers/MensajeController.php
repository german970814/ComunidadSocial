<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Conversacion;

class MensajeController extends Controller {

    public function get_conversacion(Request $request, $id) {
        $usuario_receptor = \App\Models\Usuario::findOrFail($id);
        $usuario_emisor = \Auth::guard()->user()->usuario;

        $conversacion = Conversacion::where([
            ['emisor_id', $usuario_emisor->id],
            ['receptor_id', $usuario_receptor->id]
        ])->orWhere([
            ['emisor_id', $usuario_receptor->id],
            ['receptor_id', $usuario_emisor->id]
        ])->first();

        if (!$conversacion) {
            $conversacion = Conversacion::create([
                'emisor_id' => $usuario_emisor->id,
                'receptor_id' => $usuario_receptor->id
            ]);
        }

        $chat_usuario = $conversacion->receptor_id == $usuario_emisor->id ?
            $conversacion->emisor : $conversacion->receptor;

        return response()->json([
            'code' => 200,
            'message' => 'Ok',
            'data' => array_merge($conversacion->toArray(), [
                'name' => $chat_usuario->get_full_name()
            ])
        ]);
    }

    public function ver_conversacion($id) {
        $conversacion = Conversacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        if ($conversacion->emisor_id == $usuario->id || $conversacion->receptor_id == $usuario->id) {
            $mensajes = $conversacion->mensajes()->paginate(15);
            $mensajes->withPath('/json/mensajes/conversacion/' . $conversacion->id);
            $paginated_response = $mensajes->toArray();

            $paginated_response['data'] = array_map(function($mensaje) use ($usuario) {
                if ($mensaje['usuario_id'] == $usuario->id) {
                    $mensaje['self'] = true;
                }
                return $mensaje;
            }, $paginated_response['data']);
    
            return response()->json([
                'code' => 200,
                'message' => 'Ok',
                'data' => $paginated_response
            ]);
        }
        abort(404, 'Página no existe');
    }

    public function guardar_mensaje(Request $request, $id) {
        $conversacion = Conversacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        $validated_data = $request->validate([
            'mensaje' => 'required'
        ]);

        $mensaje = Mensaje::create([
            'usuario_id' => $usuario->id,
            'conversacion_id' => $conversacion->id,
            'mensaje' => $validated_data['mensaje']
        ]);

        if ($conversacion->emisor_id == $usuario->id) {
            $receptor = $conversacion->receptor_id;
        } else {
            $receptor = $conversacion->emisor_id;
        }

        if (Redis::command('get', ['user' . $receptor])) {
            Redis::publish('user.message', json_encode([
                'user' => $receptor,
                'message' => $mensaje->toArray(),
                'emisor' => $usuario->get_full_name()
            ]));
        }

        return response()->json([
            'code' => 200,
            'message' => 'Mensaje enviado'
        ]);
    }
}