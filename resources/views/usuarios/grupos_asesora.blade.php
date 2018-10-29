@extends('usuarios.base_profile')

@section('section')
<section>
    @include('layouts.title_page', ['title_page' => 'Grupos investigación'])

    @foreach ($usuario->grupos_investigacion_asesora as $item)
        <div class="col-md-4 col-sm-6 col-xs-12 block">
            @include('grupos.grupo_card')
        </div>
    @endforeach

    @include('layouts.title_page', ['title_page' => 'Redes temáticas'])

    @foreach ($usuario->redes_tematicas_asesora as $item)
        <div class="col-md-4 col-sm-6 col-xs-12 block">
            @include('grupos.grupo_card')
        </div>
    @endforeach
</section>
@endsection