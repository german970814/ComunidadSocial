<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use \App\Models\ComentarioPost;

class Post extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'posts';

    /**
     * Campos que se pueden llenar con el metodo create, update
     */
    protected $fillable = [
        'mensaje', 'autor_id', 'usuario_destino_id', 'photo', 'tipo'
    ];

    static $post_usuario_tipo = 'usuario';
    static $post_grupo_tipo = 'grupo';

    public function usuario_destino() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_destino_id');
    }

    public function autor() {
        return $this->belongsTo('\App\Models\Usuario', 'autor_id');
    }

    public function _comentarios() {
        return $this->hasMany('\App\Models\ComentarioPost')
            ->where('estado', ComentarioPost::$ACTIVO)
            ->orderBy('created_at', 'asc');
    }

    public function comentarios() {
        return $this->_comentarios()->where('mensaje', '<>', '%-like-%')->where('like', false);
    }

    public function likes() {
        return $this->_comentarios()->where('like', true)->distinct('usuario_id')->count();
    }

    public function is_self_post() {
        return $this->autor->id === $this->usuario_destino->id;
    }

    public function get_photo_url() {
        if ($this->photo && Storage::disk('local')->has($this->photo)) {
            return route('post.photo', $this->id);
        }
        return null;
    }

    public function get_url() {
        return route('post.show', $this->id);
    }
}
