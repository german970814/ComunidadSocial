<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAmistad;
use Illuminate\Support\Facades\DB;

class SolicitudAmistadController extends Controller
{
    /**
     * JSON Response
     * 
     * @param Request $request
     * @param Integer $id
     * @return JSONResponse
     */
    public function enviar_solicitud_amistad(Request $request, $id) {
        $user = \App\User::findOrFail(\Auth::guard()->user()->id)->usuario;
        $user_id = $user->id;

        $query = DB::table('solicitudes_usuario')
            ->join('solicitudes', 'solicitudes_usuario.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes_usuario.usuario_id', $id)
            ->where('solicitudes.usuario_id', $user_id);

        if (!$query->exists()) {
            $solicitud_amistad = new SolicitudAmistad;
            $solicitud_amistad->usuario_id = $user_id;
            $solicitud_amistad->aceptada = false;
            $solicitud_amistad->save();
            \App\Models\Usuario::find($id)->agregar_solicitud($solicitud_amistad);

            return response()->json([
                'code' => 200,
                'message' => 'Solicitud de amistad enviada'
            ]);
        }

        $query->delete();
        return response()->json([
            'code' => 200,
            'message' => 'Solicitud de amistad cancelada'
        ]);
    }
}
