<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\ComentarioPost;
use \App\Models\ReporteComentarioPost;

class ComentarioPostController extends Controller
{
    /**
     * JSON
     */
    public function reportar_comentario(Request $request, $id) {
        $comentario = ComentarioPost::findOrFail($id);
        $usuario = \Auth::guard()->user()->usuario;

        $query = ReporteComentarioPost::where('usuario_id', $usuario->id)
            ->where('comentario_id', $comentario->id);

        if (!$query->exists()) {
            $reporte = ReporteComentarioPost::create([
                'usuario_id' => $usuario->id,
                'comentario_id' => $comentario->id,
                'razon' => isset($request['razon']) ? $request['razon'] : ''
            ]);
    
            return response()->json([
                'code' => 200,
                'message' => 'Gracias por avisarnos, estaremos revisando este comentario pronto'
            ]);
        }

        return response()->json([
            'code' => 204,
            'message' => 'Ya ha realizado un reporte para este comentario'
        ]);
    }

    public function inactivar_comentario(Request $request, $id) {
        $comentario = ComentarioPost::findOrFail($id);
        $comentario->update(['estado', ComentarioPost::$INACTIVO]);
        return response()->json([
            'code' => 200,
            'message' => ''
        ]);
    }
}
