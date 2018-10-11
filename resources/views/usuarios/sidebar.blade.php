<aside>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">{{ $usuario->is_institucion() ? 'Información Institución' : 'Información Personal' }}</h3>
            </div>
            <div class="panel-body">
                @include('usuarios.profile_photo')
                <div class="teamInfo teamTeacher">
                    <h3 class="color-3">{{ $usuario->get_full_name() }}</h3>
                    <p>{{ $usuario->get_tipo_usuario_display() }}</p>
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
                        @if (!Auth::user()->usuario->is_amigo($usuario) && !(Auth::user()->usuario->id == $usuario->id) && !($usuario->is_institucion()) && !Auth::user()->is_institucion() && !Auth::user()->is_administrador())
                            @if (Auth::user()->usuario->solicitud_amistad_enviada($usuario))
                                <a href="{{ route('usuario.solicitar-amistad', $usuario->id) }}" data-original-title="Cancelar envío de solicitud de amistad" data-placement="bottom" data-toggle="tooltip" disabled class="link-circle bg-color-5">
                                    <i class="fa fa-user-times"></i>
                                </a>
                            @elseif (Auth::user()->usuario->no_confirma_solicitud($usuario))
                                <a href="{{ route('usuario.aceptar-solicitud-amistad', $usuario->id) }}" data-original-title="Aceptar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-1">
                                    <i class="fa fa-check"></i>
                                </a>
                                <a href="{{ route('usuario.rechazar-solicitud-amistad', $usuario->id) }}" data-original-title="Eliminar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3">
                                    <i class="fa fa-times"></i>
                                </a>
                            @else
                                <a href="{{ route('usuario.solicitar-amistad', $usuario->id) }}" data-original-title="Agregar Amigos" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-2">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            @endif
                        @elseif ($usuario->is_institucion() && !(Auth::user()->is_institucion()) && !(Auth::user()->is_administrador()))
                            @if (Auth::user()->usuario->espera_respuesta_institucion($usuario->institucion))
                                <a data-original-title="Cancelar solicitud a institución" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3">
                                    <i class="fa fa-user-times"></i>
                                </a>
                            @elseif (!Auth::user()->usuario->institucion_pertenece())
                                <a href="{{ route('institucion.solicitud-ingreso-institucion', $usuario->institucion->id) }}" data-original-title="Enviar solicitud a institución" data-institucion-id="{{ $usuario->institucion->id }}" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-2">
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
                    @if ($usuario->is_estudiante() || $usuario->is_maestro() || $usuario->is_institucion())
                    <li>
                        @if ($usuario->is_estudiante() || $usuario->is_maestro())
                        <a href="{{ route('grupos.grupos-investigacion-usuario', ['investigacion', $usuario->id]) }}">Grupos de investigación</a>
                        @elseif ($usuario->is_institucion())
                        <a href="{{ route('grupos.grupos-investigacion-institucion', ['investigacion', $usuario->id]) }}">Grupos de investigación</a>
                        @endif
                    </li>
                    <li>
                        @if ($usuario->is_estudiante() || $usuario->is_maestro())
                        <a href="{{ route('grupos.grupos-investigacion-usuario', ['tematica', $usuario->id]) }}">Redes temáticas</a>
                        @elseif ($usuario->is_institucion())
                        <a href="{{ route('grupos.grupos-investigacion-institucion', ['tematica', $usuario->id]) }}">Redes temáticas</a>
                        @endif
                    </li>
                    @endif
                    <li>
                        @if ((\Auth::guard()->user()->is_administrador() && $usuario->is_institucion()) || (\Auth::guard()->user()->is_institucion() && \Auth::guard()->user()->usuario->id == $usuario->id))
                            <a href="{{ route('institucion.solicitudes-ingreso-institucion', $usuario->institucion->id) }}">Solicitudes Ingreso Institución</a>
                        @elseif ((($usuario->is_estudiante() || $usuario->is_maestro()) && \Auth::guard()->user()->usuario->id == $usuario->id) || \Auth::guard()->user()->is_administrador())
                            <a href="{{ route('usuario.solicitudes-amistad', $usuario->id) }}">Ver solicitudes de amistad</a>
                        @endif
                    </li>
                    @if (!$usuario->is_institucion())
                        <li>
                            <a href="{{ route('usuario.amigos', $usuario->id) }}">Amigos</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('institucion.integrantes', $usuario->institucion->id) }}">Integrantes</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</aside>