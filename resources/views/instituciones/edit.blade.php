@extends('usuarios.base_profile')

@section('section')
<div class="panel panel-default formPanel">
    <div class="panel-heading bg-color-2 border-color-2">
        <h3 class="panel-title">Editar institución</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            @include('usuarios.profile_photo')
        </div>
        <div class="row">
            <form action="{{ route('institucion.update', $institucion->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-offset-5 col-xs-2">
                    <button class="btn btn-primary" type="submit">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset('js/instituciones.js') }}"></script>
@endsection