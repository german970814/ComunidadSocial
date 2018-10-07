<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteComentarioPost extends Model
{
    protected $table = 'reportes_comentarios_posts';

    protected $fillable = [
        'razon', 'usuario_id', 'post_id', 'comentario_id'
    ];
}