<div class="thumbnail thumbnailContent">
    <a href="{{ route('grupos.show', $item->id) }}"><img src="{{ $item->get_imagen_url() }}" alt="image" class="img-responsive"></a>
    <div class="caption border-color-1">
        <h3 data-original-title="{{ $item->get_nombre() }}" data-placement="bottom" data-toggle="tooltip" title="{{ $item->get_nombre() }}"><a href="{{ route('grupos.show', $item->id) }}" class="color-primary">{{ $item->get_nombre() }}</a></h3>
        <p>{{ $item->descripcion ? $item->descripcion : 'Sin descripción' }}</p>
    </div>
</div>