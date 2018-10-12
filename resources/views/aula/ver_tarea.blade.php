@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || \Auth::guard()->user()->usuario->id == $tarea->maestro->id)
                @include('layouts.title_page', ['title_page' => 'Tareas', 'button' => ['type' => 'link', 'href' => route('aula.ver-entregas-tarea', $tarea->id), 'text' => 'Ver entregas']])
            @else
                @include('layouts.title_page', ['title_page' => $tarea->get_titulo()])
            @endif
        </div>
        <div class="row">
            <div class="well">
                <div>
                    <p>{{ $tarea->descripcion }}</p>
                </div>
                <span>{{ $tarea->get_tiempo_restante() }}</span>
            </div>
        </div>
        <div class="row">
            @if ($entrega && \Auth::guard()->user()->is_estudiante() && $entrega->is_editable())
                <h3>{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</h3>
                <form action="{{ route('aula.agregar-entrega', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('aula.form_entrega')
                    <button type="submit" class="btn btn-primary">{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</button>
                </form>
            @endif
        </div>
    </section>
@endsection