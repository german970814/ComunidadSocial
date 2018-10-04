<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $fillable = ['usuario_id', 'mensaje', 'leida', 'usuario_sender_id', 'link', 'tipo'];

    static $solicitud_amistad_tipo = 'solicitud-amistad';
    static $solicitud_amistad_aceptada_tipo = 'solicitud-amistad-aceptada';
    static $solicitud_amistad_message = 'Te ha enviado una solicitud de amistad';
    static $solicitud_amistad_aceptada_message = 'Ha aceptado tu solicitud de amistad';

    static $base_with_image = '
        <div class="media">
            <div class="media-left">
                <a href="%s"><img width="50" height="50" src="%s" alt="notification-image" /></a>
            </div>
            <div class="media-body">
                <a href="%s"><h4>%s</h4></a>
                <span class="amount">%s</span>
            </div>
        </div>
    ';

    public static function create_solicitud_amistad(\App\Models\Usuario $usuario, $data) {
        $data['leida'] = false;
        $data['mensaje'] = Notificacion::$solicitud_amistad_message;;
        $data['usuario_sender_id'] = $usuario->id;
        $data['link'] = $usuario->get_profile_url();
        $data['tipo'] = Notificacion::$solicitud_amistad_tipo;
        return Notificacion::create($data);
    }

    public static function create_acepted_solicitud_amistad(\App\Models\Usuario $usuario, $data) {
        $data['leida'] = false;
        $data['mensaje'] = Notificacion::$solicitud_amistad_aceptada_message;
        $data['usuario_sender_id'] = $usuario->id;
        $data['link'] = $usuario->get_profile_url();
        $data['tipo'] = Notificacion::$solicitud_amistad_aceptada_tipo;
        return Notificacion::create($data);
    }

    public function render() {
        switch($this->tipo) {
            case Notificacion::$solicitud_amistad_tipo:
                $sufix = '';
                $mensaje = sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    asset('assets/img/user.png'),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    $this->mensaje
                );

                $solicitud_amistad = DB::table('solicitudes_usuario')
                    ->join('solicitudes', 'solicitudes_usuario.solicitud_id', '=', 'solicitudes.id')
                    ->where('solicitudes_usuario.usuario_id', $this->usuario->id)
                    ->where('solicitudes.usuario_id', $this->usuario_sender->id)
                    ->first();

                if ($solicitud_amistad && !$solicitud_amistad->aceptada) {
                    $sufix = sprintf('
                        <button data-user-id="%s" class="aceptar-solicitud btn">ACEPTAR</button>
                        <button data-user-id="%s" class="no-aceptar-solicitud btn">NO ACEPTAR</button>
                    ', $this->usuario_sender->id, $this->usuario_sender->id);
                }
                return $mensaje . $sufix;
            case Notificacion::$solicitud_amistad_aceptada_tipo:
                return sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    asset('assets/img/user.png'),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    $this->mensaje
                );
            default:
                return '';
        }
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario');
    }

    public function usuario_sender()
    {
        return $this->belongsTo('App\Models\Usuario');
    }
}
