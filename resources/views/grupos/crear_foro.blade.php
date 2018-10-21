@extends('grupos.base')

@section('section')
    <section>
        @include('layouts.title_page', ['title_page' => isset($foro) ? 'Editar foro' : 'Crear Foro'])
        <div class="row">
            <form action="{{ isset($foro) ? route('grupos.actualizar-foro', $foro->id) : route('grupos.guardar-foro', $grupo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-offset-5 col-xs-2">
                    <button id="submit-foro" class="btn btn-primary" type="submit">{{ isset($foro) ? 'Editar' : 'Crear' }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection