@extends('usuarios.base_profile')

@section('section')
<div class="panel panel-default formPanel">
    <div class="panel-heading bg-color-2 border-color-2">
        <h3 class="panel-title">Editar usuario</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <form action="{{ route('usuario.update', $usuario->id) }}" method="POST">
                @csrf
                @method('put')
                @include('layouts.form')
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection