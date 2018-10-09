@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Búsqueda'])
    <div class="row">
        @foreach ($usuarios as $usuario_busqueda)
        <div class="col-sm-3 col-xs-12">
            <div class="teamContent teamAdjust">
                <div class="teamImage">
                    <img src="{{ $usuario_busqueda->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                    <div class="maskingContent">
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3><a href="{{ route('usuario.show', $usuario_busqueda->id) }}">{{ $usuario_busqueda->get_full_name() }}</a></h3>
                    <p>{{ $usuario_busqueda->tipo_usuario == 'M' ? 'Maestro' : 'Estudiante' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection