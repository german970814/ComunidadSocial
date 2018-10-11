<div class="post-create-container">
    <div class="media">
        <div class="preview">
            <img src="#" alt="post-image" class="img-preview img-responsive hidden" />
        </div>
    </div>
    <form action="{{ route('post.store', $tipo) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <a href="#" class="color-4" data-original-title="Subir una imagen" data-placement="bottom" data-toggle="tooltip">
                <input name="photo" type="file" accept="image/*" />
                <i class="fa fa-photo"></i>
            </a>
            <textarea name="mensaje" class="form-control border-color-4" placeholder="Escribe algo"></textarea>
        </div>
        @if (isset($usuario) && $usuario->id && $tipo == \App\Models\Post::$post_usuario_tipo)
            <input type="hidden" value="{{ $usuario->id }}" name="usuario_destino_id">
        @elseif (isset($grupo) && $grupo->id && $tipo == \App\Models\Post::$post_grupo_tipo)
            <input type="hidden" value="{{ $grupo->id }}" name="grupo_destino_id">
        @endif
        <button class="btn btn-primary" type="submit" disabled>Publicar</button>
    </form>
</div>