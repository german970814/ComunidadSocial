@extends('layouts/base')

@section('content')
    <section class="mainContent">
        <div class="container">
            @include('layouts/title_page', ['title_page' => 'Registro Asesor'])

            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="panel panel-default formPanel">
                        <div class="panel-heading bg-color-1 border-color-1">
                            <h3 class="panel-title">Crear usuario asesor</h3>
                        </div>
                        <div class="panel-body">
                            <form method="POST" id="form-registro" action="{{ route('admin.store-usuario-asesor') }}">
                                @csrf
                                <div class="form-group formField {{ $errors->has('tipo_documento') ? ' color-3' : ''}}">
                                    <select name="tipo_documento" class="form-control" value="{{ old('tipo_documento', '') }}">
                                        <option value="">Tipo de documento</option>
                                        @foreach (\App\Models\Usuario::$tipo_documento_opciones as $opcion => $label)
                                            @if (old('tipo_documento', '') == $opcion)
                                            <option selected value={{ $opcion }}>{{ $label }}</option>
                                            @else
                                            <option value={{ $opcion }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipo_documento'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="color-3">{{ $errors->first('tipo_documento') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group formField {{ $errors->has('numero_documento') ? ' color-3' : ''}}">
                                    <input name="numero_documento" type="number" value="{{ old('numero_documento', '') }}" class="form-control" placeholder="Número documento">
                                    @if ($errors->has('numero_documento'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="color-3">{{ $errors->first('numero_documento') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="content-hidden" style="display: none;">
                                    <div class="form-group formField {{ $errors->has('nombres') ? ' color-3' : ''}}">
                                        <input name="nombres" type="text" value="{{ old('nombres', '') }}" class="form-control" placeholder="Nombres">
                                        @if ($errors->has('nombres'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('nombres') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('apellidos') ? ' color-3' : ''}}">
                                        <input name="apellidos" type="text" value="{{ old('apellidos', '') }}" class="form-control" placeholder="Apellidos">
                                        @if ($errors->has('apellidos'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('apellidos') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('email') ? ' color-3' : ''}}">
                                        <input name="email" type="email" value="{{ old('email', '') }}" class="form-control" placeholder="Email">
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
                                        <select name="sexo" class="form-control" value="{{ old('sexo', '') }}">
                                            <option value="">Género</option>
                                            @foreach (\App\Models\Usuario::$sexo_opciones as $opcion => $label)
                                                @if (old('sexo', '') == $opcion)
                                                <option selected value={{ $opcion }}>{{ $label }}</option>
                                                @else
                                                <option value={{ $opcion }}>{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('sexo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('sexo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('grupo_etnico') ? ' color-3' : ''}}">
                                        <select name="grupo_etnico" class="form-control" value="{{ old('grupo_etnico', '') }}">
                                            <option value="">Grupo étnico</option>
                                            @foreach (\App\Models\Usuario::$grupo_etnico_opciones as $opcion => $label)
                                                @if (old('grupo_etnico', '') == $opcion)
                                                <option selected value={{ $opcion }}>{{ $label }}</option>
                                                @else
                                                <option value={{ $opcion }}>{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('grupo_etnico'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('grupo_etnico') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField {{ $errors->has('fecha_nacimiento') ? ' color-3' : ''}}">
                                        <input name="fecha_nacimiento" type="text" value="{{ old('fecha_nacimiento', '') }}" class="form-control datepicker" placeholder="Fecha Nacimiento">
                                        @if ($errors->has('fecha_nacimiento'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="color-3">{{ $errors->first('fecha_nacimiento') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group formField">
                                        <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Registrar">
                                    </div>
                                </div>
                            </form>
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
    var numero_documento = $('input[name="numero_documento"]');

    if (numero_documento.val().length >= 6) {
        $('.content-hidden').fadeIn();
    }

    function get_remote_data(e) {
        if (numero_documento.val().length >= 6) {
            $('.content-hidden').fadeIn();
            $.ajax({
                url: '{{ route("admin.asesor-remote-data") }}',
                method: 'POST',
                data: {
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
});
</script>
@endsection