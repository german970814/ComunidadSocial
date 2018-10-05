<form action="{{ route('post.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <textarea name="mensaje" class="form-control border-color-4" placeholder="Escribe algo"></textarea>
    </div>
    @if ($usuario->id)
        <input type="hidden" value="{{ $usuario->id }}" name="usuario_destino_id">
    @endif
    <button class="btn btn-primary" type="submit">Publicar</button>
</form>