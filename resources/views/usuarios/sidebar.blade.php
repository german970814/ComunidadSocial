<aside>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">Información Personal</h3>
            </div>
            <div class="panel-body">
                @include('usuarios.profile_photo')
                <div class="teamInfo teamTeacher">
                    <h3 class="color-3">{{ $usuario->get_full_name() }}</h3>
                    <p>{{ $usuario->tipo_usuario === 'M' ? 'Maestro' : 'Estudiante' }}</p>
                </div>
                <div class="actions">
                    @auth
                        @if (Auth::user()->usuario->id == $usuario->id)
                            @if ($usuario->is_institucion())
                                <a data-original-title="Editar" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3" href="{{ route('institucion.editar') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @else
                                <a data-original-title="Editar" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3" href="{{ route('usuario.edit', $usuario->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if (!Auth::user()->usuario->is_amigo($usuario) && !(Auth::user()->usuario->id == $usuario->id) && !($usuario->is_institucion()))
                            @if (Auth::user()->usuario->solicitud_amistad_enviada($usuario))
                                <a data-original-title="Esperando respuesta solicitud" data-placement="bottom" data-toggle="tooltip" disabled class="link-circle bg-color-5">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            @elseif (Auth::user()->usuario->no_confirma_solicitud($usuario))
                                <a data-original-title="Aún no confirmas la solicitud, deseas aceptar?" data-placement="bottom" data-toggle="tooltip" disabled class="link-circle bg-color-6">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            @else
                                <a data-original-title="Agregar Amigos" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-2 solicitud-amistad">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            @endif
                        @endif
                    @endauth
                </div>
                <ul class="list-unstyled categoryItem">
                    <li>
                        <a href="{{ route('usuario.show', $usuario->id) }}">Muro</a>
                    </li>
                    <li>
                        <a href="{{ route('usuario.detail', $usuario->id) }}">Información</a>
                    </li>
                    <li>
                        <a href="#">Fotos</a>
                    </li>
                    <li>
                        <a href="#">Mis grupos de investigación</a>
                    </li>
                    <li>
                        <a href="#">Mis redes temáticas</a>
                    </li>
                    <li>
                        <a href="{{ route('usuario.amigos', $usuario->id) }}">Mis amigos</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>