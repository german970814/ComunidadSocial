@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Solicitudes de Amistad'])
    <div class="row">
        @foreach ($usuario->solicitudes_amistad_pendientes() as $solicitud)
        <div class="col-sm-3 col-xs-12">
            <div class="teamContent teamAdjust">
                <div class="teamImage">
                    <img src="{{ $solicitud->usuario->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                    <div class="maskingContent">
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3><a href="{{ route('usuario.show', $solicitud->usuario->id) }}">{{ $solicitud->usuario->get_full_name() }}</a></h3>
                    <p>{{ $solicitud->usuario->get_tipo_usuario_display() }}</p>
                </div>
                <div class="row solicitudes-actions" style="text-align: center;">
                    <a href="{{ route('usuario.aceptar-solicitud-amistad', $solicitud->usuario->id) }}" data-original-title="Aceptar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-1">
                        <i class="fa fa-check"></i>
                    </a>
                    <a href="{{ route('usuario.rechazar-solicitud-amistad', $solicitud->usuario->id) }}" data-original-title="Eliminar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection