@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || \Auth::guard()->user()->usuario->id == $examen->maestro->id)
                @include('layouts.title_page', ['title_page' => 'examens', 'button' => ['type' => 'link', 'href' => route('aula.entregas-examen', $examen->id), 'text' => 'Ver entregas']])
            @elseif (\Auth::guard()->user()->is_estudiante() && $examen->is_activo())
                @include('layouts.title_page', ['title_page' => 'examens', 'button' => ['type' => 'link', 'href' => route('aula.examen-estudiante', $examen->id), 'text' => 'Tomar prueba']])
            @else
                @include('layouts.title_page', ['title_page' => $examen->get_titulo()])
            @endif
        </div>
        <div class="row">
            <div class="well">
                @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || \Auth::guard()->user()->usuario->id == $examen->maestro->id)
                <div class="well-actions pull-right" style="position: relative;">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-warning"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-left">
                        <li class="">
                            <a href="{{ route('aula.editar-examen', $examen->id) }}">Editar</a>
                        </li>
                    </ul>
                </div>
                @endif
                <div>
                    <p>{{ $examen->descripcion }}</p>
                </div>
                <span>{{ $examen->get_tiempo_restante() }}</span>
            </div>
        </div>
        <div class="row">
            {{-- @if (((isset($entrega) && $entrega->is_editable()) && \Auth::guard()->user()->is_estudiante()) || \Auth::guard()->user()->is_estudiante())
                <h3 style="overflow: inherit;">{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</h3>
                @if ($entrega->archivo)
                    @include('aula.documento_entrega')
                @endif
                <div class="row">
                    <form action="{{ route('aula.agregar-entrega', $examen->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('aula.form_entrega')
                        <button type="submit" class="btn btn-primary">{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</button>
                    </form>
                </div>
            @endif --}}
        </div>
    </section>
@endsection