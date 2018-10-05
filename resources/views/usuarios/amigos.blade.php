@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Amigos'])
    <div class="row">
        @foreach ($usuario->amigos() as $amigo)
        <div class="col-sm-3 col-xs-12">
            <div class="teamContent teamAdjust">
                <div class="teamImage">
                    <img src="{{ $amigo->get_profile_photo_url() }}" alt="img-friend" class="img-circle img-responsive">
                    <div class="maskingContent">
                        <ul class="list-inline">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3><a href="{{ route('usuario.show', $amigo->id) }}">{{ $amigo->get_full_name() }}</a></h3>
                    <p>{{ $amigo->tipo_usuario == 'M' ? 'Maestro' : 'Estudiante' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection