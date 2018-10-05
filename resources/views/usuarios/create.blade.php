@extends('layouts/base')

@section('content')
    <section class="mainContent">
        <div class="container">
            @include('layouts/title_page', ['title_page' => 'Registro'])

            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <form method="POST" action="{{ route('usuario.store') }}">
                        @csrf
                        <div class="form-group">
                            <input name="nombres" type="text" class="form-control border-color-1" placeholder="Nombres" />
                            @if($errors->has('nombres'))
                            @foreach ($errors->get('nombres') as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <input name="apellidos" type="text" class="form-control border-color-2" placeholder="Apellidos" />
                            @if($errors->has('apellidos'))
                            @foreach ($errors->get('apellidos') as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <input name="email" type="email" class="form-control border-color-3" placeholder="Email" />
                            @if($errors->has('email'))
                            @foreach ($errors->get('email') as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <input name="password" type="password" class="form-control border-color-4" placeholder="Contraseña" />
                            @if($errors->has('password'))
                            @foreach ($errors->get('password') as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <select name="tipo_documento" class="form-control border-color-5">
                                        <option value="">Tipo de documento</option>
                                        <option value="CC">Cedula de ciudadanía</option>
                                        <option value="TI">Tarjeta de identidad</option>
                                        <option value="RG">Registro civil</option>
                                        <option value="NES">Número establecido por la secretaría</option>
                                        <option value="NIP">Número de identificación personal</option>
                                        <option value="NUIP">Número único de identificación personal</option>
                                    </select>
                                    @if($errors->has('tipo_documento'))
                                    @foreach ($errors->get('tipo_documento') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input name="numero_documento" type="number" class="form-control border-color-6" placeholder="Número documento" />
                                    @if($errors->has('numero_documento'))
                                    @foreach ($errors->get('numero_documento') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <select name="sexo" class="form-control border-color-1">
                                        <option value="">Sexo</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                    @if($errors->has('sexo'))
                                    @foreach ($errors->get('sexo') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    <select name="grupo_etnico" class="form-control border-color-2">
                                        <option value="">Grupo étnico</option>
                                        <option value="IN">Indigenas</option>
                                        <option value="AF">Afrocolombianos</option>
                                        <option value="RO">ROM</option>
                                        <option value="NI">Ninguno</option>
                                    </select>
                                    @if($errors->has('grupo_etnico'))
                                    @foreach ($errors->get('grupo_etnico') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input name="fecha_nacimiento" type="text" class="form-control border-color-2" placeholder="Fecha de nacimiento" />
                                    @if($errors->has('fecha_nacimiento'))
                                    @foreach ($errors->get('fecha_nacimiento') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    <select name="tipo_usuario" class="form-control border-color-3">
                                        <option value="">Tipo de Usuario</option>
                                        <option value="M">Maestro</option>
                                        <option value="E">Estudiante</option>
                                    </select>
                                    @if($errors->has('tipo_usuario'))
                                    @foreach ($errors->get('tipo_usuario') as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Registrar</button>
                    </form>
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