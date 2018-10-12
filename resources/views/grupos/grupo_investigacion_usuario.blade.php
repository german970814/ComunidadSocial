@extends('usuarios.base_profile')

@section('section')
<section>
    @if ((\Auth::guard()->user()->usuario->is_institucion() && \Auth::guard()->user()->usuario->institucion->id == $usuario->institucion->id) || \Auth::guard()->user()->is_administrador())
        @include('layouts.title_page', ['title_page' => $title, 'button' => ['type' => 'link', 'href' => route('grupos.create', [$tipo, $usuario->institucion->id]), 'text' => $tipo == 'tematica' ? 'Crear Red' : 'Crear Grupo']])
    @else
        @include('layouts.title_page', ['title_page' => $title])
    @endif
    <div class="row">
        @foreach ($grupos as $item)
        <div class="col-md-4 col-sm-6 col-xs-12 block">
            <div class="thumbnail thumbnailContent">
                <a href="{{ route('grupos.show', $item->id) }}"><img src="{{ $item->get_imagen_url() }}" alt="image" class="img-responsive"></a>
                <div class="caption border-color-1">
                    <h3 data-original-title="{{ $item->get_nombre() }}" data-placement="bottom" data-toggle="tooltip" title="{{ $item->get_nombre() }}"><a href="{{ route('grupos.show', $item->id) }}" class="color-primary">{{ $item->get_nombre() }}</a></h3>
                    <p>{{ $item->descripcion ? $item->descripcion : 'Sin descripción' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        @include('layouts.pagination')
    </div>
</section>
@endsection