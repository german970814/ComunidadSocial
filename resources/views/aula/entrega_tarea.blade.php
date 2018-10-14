@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @include('layouts.title_page', ['title_page' => 'Entrega de: ' . $entrega->usuario->get_full_name()])
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
            <h3>Entrega</h3>
            <div class="well">
                @if ($entrega->archivo)
                    @include('aula.documento_entrega')
                @endif
                <div>
                    <p>{{ $entrega->descripcion }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection