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
                @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || \Auth::guard()->user()->usuario->id == $tarea->maestro->id)
                <div class="well-actions pull-right" style="position: relative;">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-warning"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-left">
                        <li class="">
                            <a href="{{ route('aula.editar-tarea', $tarea->id) }}">Editar</a>
                        </li>
                    </ul>
                </div>
                @endif
                <div>
                    <p>{{ $tarea->descripcion }}</p>
                </div>
                @if ($tarea->has_documentos())
                <div class="space-25"></div>
                <div>
                    <strong>Documentos</strong>
                    <div class="documentos-container row">
                        @foreach ($tarea->documentos->all() as $documento)
                        <div class="col-sm-12 col-md-3 text-center">
                            <div style="position: relative;">
                                <img class="icon-file" src="{{ $documento->get_icon() }}" />
                                @if (\Auth::guard()->user()->is_administrador() || \Auth::guard()->user()->is_asesor() || \Auth::guard()->user()->usuario->id == $tarea->maestro->id)
                                    <span data-original-title="Eliminar documento" data-placement="bottom" data-toggle="tooltip" class="document-remove badge bg-color-3"><a style="color: white;" href="{{ $documento->get_eliminar_url() }}"><i class="fa fa-times"></i></a></span>
                                @endif
                            </div>
                            <span data-original-title="Descargar" data-placement="bottom" data-toggle="tooltip" style="display: block;">
                                <a class="color-primary" href="{{ $documento->get_url() }}" target="_blank">
                                    <strong>{{ $documento->get_nombre() }}</strong>
                                </a>
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <span>{{ $tarea->get_tiempo_restante() }}</span>
            </div>
        </div>
        <div class="row">
            @if ((($entrega && $entrega->is_editable()) && \Auth::guard()->user()->is_estudiante()) || \Auth::guard()->user()->is_estudiante())
                <h3 style="overflow: inherit;">{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</h3>
                @if ($entrega->archivo)
                    @include('aula.documento_entrega')
                @endif
                <div class="row">
                    <form action="{{ route('aula.agregar-entrega', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('aula.form_entrega')
                        <button type="submit" class="btn btn-primary">{{ !$entrega ? 'Agregar entrega' : 'Editar entrega' }}</button>
                    </form>
                </div>
            @endif
        </div>
    </section>
@endsection