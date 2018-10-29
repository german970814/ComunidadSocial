@extends('grupos.base')

@section('section')
    <section>
        <div class="row">
            @include('layouts.title_page', ['title_page' => 'Entregas: ' . $tarea->get_titulo()])
        </div>
        <div class="row">
            <table class="table table-responsive table-bordered">
                <thead>
                    <th>Estudiante</th>
                    <th>Link</th>
                    <th>Nota</th>
                </thead>
                <tbody>
                    @foreach ($tarea->entregas->all() as $entrega)
                        <tr>
                            <td>{{ $entrega->usuario->get_full_name() }}</td>
                            <td><a href="{{ route('aula.ver-entrega', $entrega->id) }}">Link a Entrega</a></td>
                            <td>0</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection