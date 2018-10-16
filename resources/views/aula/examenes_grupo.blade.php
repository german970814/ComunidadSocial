@extends('grupos.base')

@section('section')
    <section>
        @if (\App\Libraries\Permissions::has_perm('crear_examen', ['grupo' => $grupo]))
            @include('layouts.title_page', ['title_page' => 'Exámenes', 'button' => ['type' => 'link', 'href' => route('aula.crear-examen', $grupo->id), 'text' => 'Crear Examen']])
        @else
            @include('layouts.title_page', ['title_page' => 'Exámenes'])
        @endif
        <div class="row">
            @php
                $_examenes = \Auth::guard()->user()->is_estudiante() ? $grupo->examenes_activos : $grupo->examenes;
            @endphp
            @if ($_examenes->all())
                @foreach($_examenes->all() as $examen)
                <div class="col-md-6 col-xs-12">
                    <a href="{{ $examen->get_url() }}">
                        <div class="well">
                            <h3>{{ $examen->get_titulo() }}</h3>
                            <span>{{ $examen->get_tiempo_restante() }}</span>
                        </div>
                    </a>
                    <div class="space-25"></div>
                </div>
                @endforeach
            @else
                @include('layouts.vacio', ['text' => 'No hay examenes para mostrar'])
            @endif
        </div>
    </section>
@endsection