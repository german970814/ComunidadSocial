<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'notificaciones';

    /**
     * Campos que se pueden llenar con el metodo create, update
     */
    protected $fillable = ['usuario_id', 'mensaje', 'leida', 'usuario_sender_id', 'link', 'tipo'];

    static $solicitud_amistad_tipo = 'solicitud-amistad';
    static $solicitud_amistad_aceptada_tipo = 'solicitud-amistad-aceptada';
    static $like_post_tipo = 'like-own-post';
    static $publicacion_post_tipo = 'publicacion-post';
    static $comentario_post_tipo = 'comentario-post';
    static $solicitud_amistad_message = 'Te ha enviado una solicitud de amistad';
    static $solicitud_amistad_aceptada_message = 'Ha aceptado tu solicitud de amistad';
    static $like_post_message = 'Le ha gustado tu <a href="%s">publicación</a>';
    static $publicacion_post_message = 'Ha realizado una <a href="%s">publicación</a> en tu <a href="%s">muro</a>';
    static $comentario_post_message = 'Ha realizado un <a href="%s">comentario</a> a una <a href="%s">publicación</a>';

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

    public static function create_like_post(\App\Models\Post $post, \App\Models\Usuario $usuario, $data) {
        $data['leida'] = false;
        $data['mensaje'] = Notificacion::$like_post_message;
        $data['usuario_sender_id'] = $usuario->id;
        $data['link'] = $post->get_url();
        $data['tipo'] = Notificacion::$like_post_tipo;
        return Notificacion::create($data);
    }

    public static function create_publicacion_post(\App\Models\Post $post, \App\Models\Usuario $usuario, $data) {
        $data['leida'] = false;
        $data['mensaje'] = Notificacion::$publicacion_post_message;
        $data['usuario_sender_id'] = $usuario->id;
        $data['link'] = $post->get_url();
        $data['tipo'] = Notificacion::$publicacion_post_tipo;
        return Notificacion::create($data);
    }

    public static function create_comentario_post(\App\Models\ComentarioPost $comentario, $data) {
        $data['leida'] = false;
        $data['link'] = $comentario->get_url();
        $data['usuario_sender_id'] = $comentario->usuario->id;
        $data['tipo'] = Notificacion::$comentario_post_tipo;
        $data['mensaje'] = sprintf(
            Notificacion::$comentario_post_message,
            $comentario->get_url(),
            $comentario->post->get_url()
        );
        return Notificacion::create($data);
    }

    public function render() {
        switch($this->tipo) {
            case Notificacion::$solicitud_amistad_tipo:
                $sufix = '';
                $mensaje = sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_profile_photo_url(),
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
                    $this->usuario_sender->get_profile_photo_url(),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    $this->mensaje
                );
            case Notificacion::$like_post_tipo:
                return sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_profile_photo_url(),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    sprintf($this->mensaje, $this->link)
                );
            case Notificacion::$publicacion_post_tipo:
                return sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_profile_photo_url(),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    sprintf($this->mensaje, $this->link, $this->link)
                );
            default:
                return sprintf(
                    Notificacion::$base_with_image,
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_profile_photo_url(),
                    $this->usuario_sender->get_profile_url(),
                    $this->usuario_sender->get_full_name(),
                    $this->mensaje
                );
        }
    }

    private static function _notificaciones_usuario($user) {
        return Notificacion::where('usuario_id', $user->usuario->id);
    }

    public static function notificaciones_pendientes_usuario($user) {
        return Notificacion::_notificaciones_usuario($user)
            ->where('leida', false)->count(); 
    }

    public static function notificaciones_usuario($user) {
        return Notificacion::_notificaciones_usuario($user)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function ultima_notificacion_usuario($user) {
        return Notificacion::_notificaciones_usuario($user)
            ->orderBy('created_at', 'desc')
            ->first();
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
