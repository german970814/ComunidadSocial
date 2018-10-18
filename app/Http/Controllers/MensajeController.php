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

        return response()->json([
            'code' => 200,
            'message' => 'Ok',
            'data' => $conversacion->toArray()
        ]);
    }

    public function ver_conversacion($id) {
        $conversacion = Conversacion::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        $mensajes = $conversacion->mensajes->map(function($mensaje) use ($usuario) {
            if ($mensaje->usuario_id == $usuario->id) {
                $mensaje->self = true;
            }
            return $mensaje;
        });

        return response()->json([
            'code' => 200,
            'message' => 'Ok',
            'data' => [
                'mensajes' => $mensajes->toArray()
            ]
        ]);
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
                'message' => $mensaje->toArray()
            ]));
        }

        return response()->json([
            'code' => 200,
            'message' => 'Mensaje enviado'
        ]);
    }
}