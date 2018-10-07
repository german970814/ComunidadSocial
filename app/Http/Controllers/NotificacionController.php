<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function read_notificacion($id) {
        $notificacion = Notificacion::findOrFail($id);
        if (!$notificacion->leida) {
            $notificacion->leida = true;
            $notificacion->update();
        }
    
        return response()->json([
            'code' => 200,
            'message' => 'Notificacion leida'
        ]);
    }

    public function user_has_new_notificacion(Request $request, $id) {
        $user = \App\Models\Usuario::find($id)->user;
        $response = response()->stream(function () use ($request, $user) {
            $_cached_notificaciones_pendientes = $request->session()->get(
                'notificaciones_pendientes', Notificacion::notificaciones_pendientes_usuario($user)
            );
            $notificaciones_pendientes = Notificacion::notificaciones_pendientes_usuario($user);
            if ($_cached_notificaciones_pendientes >= $notificaciones_pendientes) {
                echo 'data: ' . json_encode([
                    'notificaciones' => [
                        'new' => false
                    ]
                ]) . ' \n\n\n';
                $request->session()->put('notificaciones_pendientes', $notificaciones_pendientes);
                $request->session()->save();
            } else {
                $notificacion = Notificacion::ultima_notificacion_usuario($user);
                echo 'data: ' . json_encode([
                    'notificaciones' => [
                        'new' => true,
                        'notificacion' => [
                            'id' => $notificacion->id,
                            'render' => $notificacion->render()
                        ]
                    ]
                ]) . ' \n\n\n';
                $request->session()->put('notificaciones_pendientes', $notificaciones_pendientes);
                $request->session()->save();
            }
            flush();
            ob_flush();
            sleep(5);  // 5 segundos
        }, 200, [
            'Content-Type' => 'text/event-stream; charset=utf-8',
            'X-Accel-Buffering' => 'no',
            'Cach-Control' => 'no-cache'
        ]);
        return $response;
    }
}