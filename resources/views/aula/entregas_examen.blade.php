@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @include('layouts.title_page', ['title_page' => 'Entregas: ' . $examen->get_titulo()])
        </div>
        <div class="row">
            <table class="table table-responsive table-bordered">
                <thead>
                    <th>Estudiante</th>
                    <th>Link</th>
                    <th>Preguntas</th>
                    <th>Calificación</th>
                </thead>
                <tbody>
                    @foreach ($examen->entregas->all() as $entrega)
                        <tr>
                            <td>{{ $entrega->usuario->get_full_name() }}</td>
                            <td><a href="{{ route('aula.entrega-examen', $entrega->id) }}">Link a Entrega</a></td>
                            <td>{{ count($entrega->get_respuestas_correctas()) }}/{{ count($preguntas) }}</td>
                            <td>{{ $entrega->get_calificacion() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection