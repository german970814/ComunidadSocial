@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Búsqueda'])
    @foreach ($usuarios as $key => $busqueda_data)
        @if (count($busqueda_data['data']))
            @include('layouts.title_page', ['title_page' => $busqueda_data['title']])
            <div class="row">
                @foreach ($busqueda_data['data'] as $usuario_busqueda)
                <div class="col-sm-3 col-xs-12">
                    <div class="teamContent teamAdjust">
                        <div class="teamImage">
                            <img src="{{ $usuario_busqueda->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                            {{-- <div class="maskingContent">
                                <ul class="list-inline">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div> --}}
                        </div>
                        <div class="teamInfo teamTeacher">
                            <h3><a href="{{ route('usuario.show', $usuario_busqueda->id) }}">{{ $usuario_busqueda->get_full_name() }}</a></h3>
                            <p>{{ $usuario_busqueda->get_tipo_usuario_display() }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endforeach
</section>
@endsection