<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteComentarioPost extends Model
{
    protected $table = 'reportes_comentarios_posts';

    protected $fillable = [
        'razon', 'usuario_id', 'post_id', 'comentario_id'
    ];

    static $COMENTARIO = 'C';
    static $POST = 'P';

    public function post() {
        return $this->belongsTo('\App\Models\Post', 'post_id');
    }

    public function comentario() {
        return $this->belongsTo('\App\Models\ComentarioPost', 'comentario_id');
    }

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }

    public function get_razon() {
        if ($this->razon) {
            return $this->razon;
        }
        if ($this->get_tipo() == self::$COMENTARIO) {
            return 'Reporte a comentario de ' . $this->comentario->usuario->get_full_name();
        }
        return 'Reporte a publicación de ' . $this->post->autor->get_full_name();
    }

    public function get_tipo() {
        if ($this->comentario) {
            return self::$COMENTARIO;
        }
        return self::$POST;
    }
}