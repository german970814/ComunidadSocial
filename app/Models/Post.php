<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'mensaje', 'autor_id', 'usuario_destino_id'
    ];

    public function usuario_destino() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_destino_id');
    }

    public function autor() {
        return $this->belongsTo('\App\Models\Usuario', 'autor_id');
    }

    public function _comentarios() {
        return $this->hasMany('\App\Models\ComentarioPost')->orderBy('created_at', 'asc');
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

    public function get_url() {
        return route('post.show', $this->id);
    }
}