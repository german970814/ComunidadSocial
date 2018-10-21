@extends('grupos.base')

@section('section')
<section>
    @if (\App\Libraries\Permissions::has_perm('crear_foro', ['grupo' => $grupo]))
        @include('layouts.title_page', ['title_page' => 'Foro', 'button' => ['type' => 'link', 'href' => route('grupos.crear-foro', $grupo->id), 'text' => 'Nuevo Tema']])
    @else
        @include('layouts.title_page', ['title_page' => 'Foro'])
    @endif
    <div class="row">
        @if ($grupo->foros->all())
            @foreach($grupo->foros->all() as $foro)
            <div class="col-xs-12">
                <div class="well">
                    <h3><a href="{{ $foro->get_url() }}">{{ $foro->tema }}</a></h3>
                    <p>{{ $foro->respuestas()->count() }} Respuestas</p>
                    <span>Por: <a href="{{ $foro->usuario->get_profile_url() }}">{{ $foro->usuario->get_full_name() }}</a></span>
                </div>
                <div class="space-25"></div>
            </div>
            @endforeach
        @else
            @include('layouts.vacio', ['text' => 'No hay foros para mostrar'])
        @endif
    </div>
</section>
@endsection