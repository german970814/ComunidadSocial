@extends('usuarios.base_profile')


@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Solicitudes ingreso institución'])
    <div class="row">
        @foreach ($solicitudes as $solicitud)
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
                    <h3><a href="#">{{ $solicitud->usuario->get_full_name() }}</a></h3>
                    <p>{{ $solicitud->usuario->tipo_usuario == 'M' ? 'Maestro' : 'Estudiante' }}</p>
                </div>
                <div class="row solicitudes-actions" style="text-align: center;">
                    <a href="{{ route('institucion.aceptar-solicitud-ingreso-institucion', $solicitud->id) }}" data-original-title="Aceptar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-1">
                        <i class="fa fa-check"></i>
                    </a>
                    <a href="{{ route('institucion.rechazar-solicitud-ingreso-institucion', $solicitud->id) }}" data-original-title="Eliminar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection

@section('custom_script')
{{-- <script src="{{ asset('js/instituciones.js') }}"></script> --}}
@endsection