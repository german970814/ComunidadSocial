@extends('usuarios.base_profile')


@section('section')
<section class="mainContent">
    @include('layouts.title_page', ['title_page' => 'Crear instituciones'])
    <div class="row">
        <form action="{{ route('institucion.store') }}" method="POST">
            @csrf
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

@section('custom_script')
<script src="{{ asset('js/instituciones.js') }}"></script>
@endsection