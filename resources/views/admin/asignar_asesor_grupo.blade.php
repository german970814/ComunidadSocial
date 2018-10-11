@extends('grupos.base')

@section('section')
    <section>
        @include('layouts.title_page', ['title_page' => 'Asignar asesor a grupo'])
        <div class="row">
            <form action="{{ route('admin.guardar-asesor-grupo', $grupo->id) }}" method="POST">
                @csrf
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-offset-5 col-xs-2">
                    <button class="btn btn-primary" type="submit">Asignar</button>
                </div>
            </form>
        </div>
    </section>
@endsection