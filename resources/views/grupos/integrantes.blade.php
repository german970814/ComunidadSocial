@extends('grupos.base')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Estudiantes'])
    <div class="row">
        @foreach ($grupo->estudiantes() as $estudiante)
        <div class="col-sm-3 col-xs-12">
            <div class="teamContent teamAdjust">
                <div class="teamImage">
                    <img src="{{ $estudiante->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                    <div class="maskingContent">
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3><a href="{{ route('usuario.show', $estudiante->id) }}">{{ $estudiante->get_full_name() }}</a></h3>
                    <p>{{ $estudiante->get_tipo_usuario_display() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @include('layouts.title_page', ['title_page' => 'Maestros'])
    <div class="row">
        @foreach ($grupo->maestros() as $maestro)
        <div class="col-sm-3 col-xs-12">
            <div class="teamContent teamAdjust">
                <div class="teamImage">
                    <img src="{{ $maestro->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                    <div class="maskingContent">
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3><a href="{{ route('usuario.show', $maestro->id) }}">{{ $maestro->get_full_name() }}</a></h3>
                    <p>{{ $maestro->get_tipo_usuario_display() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection