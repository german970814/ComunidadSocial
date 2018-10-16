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
                    <th>Nota</th>
                </thead>
                <tbody>
                    @foreach ($examen->entregas->all() as $entrega)
                        <tr>
                            <td>{{ $entrega->usuario->get_full_name() }}</td>
                            <td><a href="{{ route('aula.entrega-examen', $entrega->id) }}">Linkg a Entrega</a></td>
                            <td>{{ count($entrega->get_respuestas_correctas()) }}/{{ count($preguntas) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection