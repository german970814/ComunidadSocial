@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @include('layouts.title_page', ['title_page' => 'Entrega de: ' . $entrega->usuario->get_full_name()])
        </div>
        <div class="row">
            <div class="well">
                <div>
                    <p>{{ $examen->descripcion }}</p>
                </div>
                <span>{{ $examen->get_tiempo_restante() }}</span>
            </div>
        </div>
        <div class="row">
            <h3>Entrega</h3>
            @foreach ($preguntas as $pregunta)
            <div class="well">
                <div class="">
                    <div class="pregunta-titulo">{{ $pregunta->titulo }}</div>
                    <ol class="preguntas-opciones">
                        @foreach ($pregunta->opciones as $opcion)
                        <li class="">
                            <div class="{{ in_array($opcion->id, ($entrega->get_respuesta_pregunta($pregunta->id) ? $entrega->get_respuesta_pregunta($pregunta->id)->respuestas : [])) ? 'border-respuesta' : '' }}">
                                <p class="{{ $opcion->respuesta ? 'color-2' : '' }}">{{ $opcion->text }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endsection