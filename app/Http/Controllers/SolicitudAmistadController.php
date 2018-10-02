<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAmistad;
use Illuminate\Support\Facades\DB;

class SolicitudAmistadController extends Controller
{
    public function enviar_solicitud_amistad(Request $request, $id) {
        $user_id = \App\User::find(\Auth::guard()->user()->id)->id;
        // $query = DB::table('solicitudes_usuario')
        //     ->where('usuario_id', $id);
        // if (!$query->exists()) {
            $solicitud_amistad = new SolicitudAmistad;
            $solicitud_amistad->usuario_id = $user_id;
            $solicitud_amistad->aceptada = false;
            $solicitud_amistad->save();
            \App\Models\Usuario::find($id)->agregar_solicitud($solicitud_amistad);
        // }
        return [
            'code' => 200,
            'message' => 'Solicitud de amistad enviada'
        ];




        // $user_id = \App\User::find(\Auth::guard()->user()->id)->id;

        // $query = DB::table('solicitudes')
        //     ->where('usuario_solicita_id', $user_id)
        //     ->where('usuario_solicitud_id', $id);
        // if (!$query->exists()) {
        //     $solicitud_amistad = new SolicitudAmistad;
        //     $solicitud_amistad->usuario_solicita_id = $user_id;
        //     $solicitud_amistad->usuario_solicitud_id = $id;
        //     $solicitud_amistad->aceptada = false;
        //     $solicitud_amistad->save();

        //     return [
        //         'code' => 200,
        //         'message' => 'Solicitud de amistad enviada'
        //     ];
        // }

        // $query->delete();
        // return [
        //     'code' => 200,
        //     'message' => 'Solicitud de amistad cancelada'
        // ];
    }
}
