@extends('layouts.base')


@section('content')
<section class="mainContent">
    @include('layouts.title_page', ['title_page' => 'Crear lineas de investigacion'])
    <div class="row">
        <form action="{{ route('linea-investigacion.store') }}" method="POST">
            @csrf
            <input type="hidden" value="{{ $tipo }}" name="tipo" />
            <div class="col-xs-12">
                @include('layouts.form', ['with_labels' => true])
            </div>
            <div class="col-xs-offset-5 col-xs-2">
                <button class="btn btn-primary" type="submit">Crear</button>
            </div>
        </form>
    </div>
</section>
@endsection