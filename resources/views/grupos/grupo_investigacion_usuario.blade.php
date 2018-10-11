@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => $title])
    <div class="row">
        @foreach ($grupos as $item)
        <div class="col-md-4 col-sm-6 col-xs-12 block">
            <div class="thumbnail thumbnailContent">
                <a href="{{ route('grupos.show', $item->id) }}"><img src="{{ $item->get_imagen_url() }}" alt="image" class="img-responsive"></a>
                <div class="caption border-color-1">
                    <h3><a href="{{ route('grupos.show', $item->id) }}" class="color-1">{{ $item->get_nombre() }}</a></h3>
                    <p>{{ $item->descripcion ? $item->descripcion : 'Sin descripción' }}</p>
                    {{-- <ul class="list-inline btn-yellow">
                        <li><a href="#" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Ver perfil</a></li>
                        <li><a href="#" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                    </ul> --}}
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