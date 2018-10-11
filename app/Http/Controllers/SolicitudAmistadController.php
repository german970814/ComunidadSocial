<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAmistad;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helper;

class SolicitudAmistadController extends Controller
{
    /**
     * JSON Response
     * 
     * @param Request $request
     * @param Integer $id
     * @return JSONResponse
     */
    public function solicitud_amistad(Request $request, $id) {
        $user = \Auth::guard()->user()->usuario;
        $user_id = $user->id;

        $query = DB::table('solicitudes_usuario')
            ->join('solicitudes', 'solicitudes_usuario.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes_usuario.usuario_id', $id)
            ->where('solicitudes.usuario_id', $user_id);

        if (!$query->exists()) {
            if ($user->is_estudiante() || $user->is_maestro()) {
                Helper::atomic_transaction(function () use ($user_id, $id, $user) {
                    $solicitud_amistad = new SolicitudAmistad;
                    $solicitud_amistad->usuario_id = $user_id;
                    $solicitud_amistad->aceptada = false;
                    $solicitud_amistad->save();
                    \App\Models\Usuario::find($id)->agregar_solicitud($solicitud_amistad);
                    \App\Models\Notificacion::create_solicitud_amistad($user, ['usuario_id' => $id]);
                });
            }

            return back()->with('success', 'Solicitud de amistad enviada');
        }

        $query->delete();
        return back()->with('info', 'Solicitud de amistad cancelada');
    }

    public function aceptar_solicitud_amistad(Request $request, $id) {
        $user = \Auth::guard()->user()->usuario;

        $query = DB::table('solicitudes_usuario')
            ->join('solicitudes', 'solicitudes_usuario.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes_usuario.usuario_id', $user->id)
            ->where('solicitudes.usuario_id', $id)
            ->first();

        if ($query) {
            $solicitud_amistad = null;
            if ($user->is_estudiante() || $user->is_maestro()) {
                Helper::atomic_transaction(function () use ($user, $id, $query, &$solicitud_amistad) {
                    $solicitud_amistad = SolicitudAmistad::find($query->solicitud_id);
                    $solicitud_amistad->aceptada = true;
                    $solicitud_amistad->update();
                    \App\Models\Notificacion::create_acepted_solicitud_amistad($user, ['usuario_id' => $id]);
                });
            }

            return back()->with('success', sprintf('Ahora eres amigo de %s', $solicitud_amistad->usuario->get_full_name()));
        }

        return back()->with('error', 'Ha ocurrido un error con la solicitud');
    }

    public function rechazar_solicitud_amistad(Request $request, $id) {
        $user = \Auth::guard()->user()->usuario;

        $query = DB::table('solicitudes_usuario')
            ->join('solicitudes', 'solicitudes_usuario.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes_usuario.usuario_id', $user->id)
            ->where('solicitudes.usuario_id', $id)
            ->first();

        if ($query && ($user->is_estudiante() || $user->is_maestro())) {
            $solicitud_amistad = SolicitudAmistad::find($query->solicitud_id);
            if ($solicitud_amistad) $solicitud_amistad->delete();
            return back()->with('info', 'Se ha rechazado la solicitud de amistad');
        }

        return back()->with('error', 'Ha ocurrido un error con la solicitud');
    }
}
