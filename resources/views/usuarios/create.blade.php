@extends('layouts/base')

@section('content')
    <section class="mainContent">
        <div class="container">
            @include('layouts/title_page', ['title_page' => 'Registro'])

            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="panel panel-default formPanel">
                        <div class="panel-heading bg-color-1 border-color-1">
                            <h3 class="panel-title">Usuario nuevo</h3>
                        </div>
                        <div class="panel-body">
                            <form method="POST" id="form-registro" action="{{ route('usuario.store') }}">
                                @csrf
                                <div class="form-group formField {{ $errors->has('tipo_usuario') ? ' color-3' : ''}}">
                                    <select name="tipo_usuario" class="form-control">
                                        <option value="">Tipo de usuario</option>
                                        <option value="{{ \App\Models\Usuario::$ESTUDIANTE }}">Estudiante</option>
                                        <option value="{{ \App\Models\Usuario::$MAESTRO }}">Maestro</option>
                                    </select>
                                    @if ($errors->has('tipo_usuario'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="color-3">{{ $errors->first('tipo_usuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group formField {{ $errors->has('tipo_documento') ? ' color-3' : ''}}">
                                    <select name="tipo_documento" class="form-control">
                                        <option value="">Tipo de documento</option>
                                        @foreach (\App\Models\Usuario::$tipo_documento_opciones as $opcion => $label)
                                            <option value={{ $opcion }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipo_documento'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="color-3">{{ $errors->first('tipo_documento') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group formField {{ $errors->has('numero_documento') ? ' color-3' : ''}}">
                                    <input name="numero_documento" type="number" class="form-control" placeholder="Número documento">
                                    @if ($errors->has('numero_documento'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="color-3">{{ $errors->first('numero_documento') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="content-hidden" style="display: none;">
                                    <div class="form-group formField {{ $errors->has('nombres') ? ' color-3' : ''}}">
                                        <input name="nombres" type="text" class="form-control" placeholder="Nombres">
                                        @if ($errors->has('nombres'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('nombres') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('apellidos') ? ' color-3' : ''}}">
                                        <input name="apellidos" type="text" class="form-control" placeholder="Apellidos">
                                        @if ($errors->has('apellidos'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('apellidos') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('email') ? ' color-3' : ''}}">
                                        <input name="email" type="email" class="form-control" placeholder="Email">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('password') ? ' color-3' : ''}}">
                                        <input name="password" type="password" class="form-control" placeholder="Contraseña">
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('sexo') ? ' color-3' : ''}}">
                                        <select name="sexo" class="form-control">
                                            <option value="">Género</option>
                                            @foreach (\App\Models\Usuario::$sexo_opciones as $opcion => $label)
                                                <option value={{ $opcion }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('sexo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('sexo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('grupo_etnico') ? ' color-3' : ''}}">
                                        <select name="grupo_etnico" class="form-control">
                                            <option value="">Grupo étnico</option>
                                            @foreach (\App\Models\Usuario::$grupo_etnico_opciones as $opcion => $label)
                                                <option value={{ $opcion }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('grupo_etnico'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('grupo_etnico') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('fecha_nacimiento') ? ' color-3' : ''}}">
                                        <input name="fecha_nacimiento" type="text" class="form-control datepicker" placeholder="Fecha Nacimiento">
                                        @if ($errors->has('fecha_nacimiento'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('fecha_nacimiento') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField">
                                        <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Log in">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <div class="panel panel-default formPanel">
                        <div class="panel-heading bg-color-1 border-color-1">
                            <h3 class="panel-title">Ya estás registrado?</h3>
                        </div>
                        <div class="panel-body">
                            @include('layouts.login')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('custom_script')
<script>
$(document).ready(function() {
    var tipo_documento = $('select[name="tipo_documento"]');
    var numero_documento = $('input[name="numero_documento"]');
    var tipo_usuario = $('select[name="tipo_usuario"]');

    function get_remote_data(e) {
        if (numero_documento.val().length >= 8 && tipo_usuario.val()) {
            $('.content-hidden').fadeIn();
            $.ajax({
                url: '{{ route("usuario.remoto") }}',
                method: 'POST',
                data: {
                    tipo_usuario: tipo_usuario.val(),
                    identificacion: numero_documento.val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: (data) => {
                    if (data.code === 200) {
                        const { object } = data;
                        $('input[name="nombres"]').val(object.nombre);
                        $('input[name="apellidos"]').val(object.apellido);
                        $('select[name="sexo"]').val(object.sexo);
                    }
                    // if (data.code === 404) {
                    //     $('input[name="nombres"]').val('');
                    //     $('input[name="apellidos"]').val('');
                    //     $('select[name="sexo"]').val('');
                    // }
                }
            });
        } else {
            $('.content-hidden').fadeOut();
        }
    }

    numero_documento.on('input', get_remote_data);
    tipo_usuario.on('input', get_remote_data);
});
</script>
@endsection