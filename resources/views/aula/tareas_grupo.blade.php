@extends('grupos.base')

@section('section')
    <section>
        @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || (\Auth::guard()->user()->is_maestro() && \Auth::guard()->user()->usuario->pertenece_grupo($grupo)))
            @include('layouts.title_page', ['title_page' => 'Tareas', 'button' => ['type' => 'link', 'href' => route('aula.crear-tarea-grupo', $grupo->id), 'text' => 'Crear Tarea']])
        @else
            @include('layouts.title_page', ['title_page' => 'Tareas'])
        @endif
        <div class="row">
            @php
                $_tareas = \Auth::guard()->user()->is_estudiante() ? $grupo->tareas_activas : $grupo->tareas;
            @endphp
            @foreach($_tareas->all() as $tarea)
            <div class="col-md-6 col-xs-12">
                <a href="{{ $tarea->get_url() }}">
                    <div class="well">
                        <h3>{{ $tarea->get_titulo() }}</h3>
                        <span>{{ $tarea->get_tiempo_restante() }}</span>
                    </div>
                </a>
                <div class="space-25"></div>
            </div>
            @endforeach
        </div>
    </section>
@endsection