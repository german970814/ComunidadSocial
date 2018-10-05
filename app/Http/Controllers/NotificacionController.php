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
}