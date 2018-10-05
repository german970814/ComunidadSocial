<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioPost extends Model
{
    protected $fillable = [
        'mensaje', 'post_id', 'usuario_id', 'like'
    ];

    public function post() {
        return $this->belongsTo('\App\Models\Post');
    }

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario');
    }

    public function get_url() {
        return route('post.show', $this->post->id) . '#comment-' . $this->id;
    }
}
