<aside>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <div class="media-profile teamAdjust">
                    <div class="sidebar-pic">
                        <img class="img-responsive" style="height: 100%; width: 100%" src="{{ $usuario->get_profile_photo_url() }}" alt="profile" />
                    </div>
                    <div class="teamInfo">
                        <h5 class="color-3">{{ $usuario->get_full_name() }}</h5>
                        <span>{{ $usuario->get_tipo_usuario_display() }}</span>
                    </div>
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
                        @elseif ($usuario->is_institucion() && \App\Libraries\Permissions::has_perm('enviar_solicitud_institucion'))
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
            </div>
        </div>
    </div>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    @if ((\Auth::guard()->user()->is_administrador() && $usuario->is_institucion()) || (\Auth::guard()->user()->is_institucion() && \Auth::guard()->user()->usuario->id == $usuario->id))
                    <li>
                        <a href="{{ route('institucion.solicitudes-ingreso-institucion', $usuario->institucion->id) }}">
                            <i class="fa fa-user-plus icon-menu"></i>
                            Solicitudes Ingreso Institución
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('usuario.show', $usuario->id) }}">
                            <i class="fa fa-home icon-menu"></i>
                            <span>Muro</span>
                        </a>
                    </li>
                    @if (!$usuario->is_institucion())
                    <li>
                        <a href="{{ route('usuario.detail', $usuario->id) }}">
                            <i class="fa fa-user icon-menu"></i>
                            Información
                        </a>
                    </li>
                    @endif
                    @if ((($usuario->is_estudiante() || $usuario->is_maestro()) && \Auth::guard()->user()->usuario->id == $usuario->id) || \Auth::guard()->user()->is_administrador())
                    <li>
                        <a href="{{ route('usuario.solicitudes-amistad', $usuario->id) }}">
                            <i class="fa fa-users icon-menu"></i>
                            Ver solicitudes de amistad
                        </a>
                    </li>
                    @endif
                    @if (($usuario->is_estudiante() || $usuario->is_maestro()) && $usuario->institucion_pertenece())
                        <li>
                            <a href="{{ route('usuario.show', $usuario->institucion_pertenece()->usuario->id) }}">
                                <i class="fa fa-university icon-menu"></i>
                                Institución a la que pertenece
                            </a>
                        </li>
                    @endif
                    @if (!$usuario->is_institucion())
                        <li>
                            <a href="{{ route('usuario.amigos', $usuario->id) }}">
                                <i class="fa fa-handshake icon-menu"></i>
                                Amigos
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('institucion.integrantes', $usuario->institucion->id) }}">
                                <i class="fa fa-home icon-menu"></i>
                                Integrantes
                            </a>
                        </li>
                    @endif
                    @if (\App\Libraries\Permissions::has_perm('ver_mensajes', $usuario))
                        <li>
                            <a href="{{ route('usuario.mensajes') }}">
                                <i class="fa fa-comments icon-menu"></i>
                                Mensajes
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @if ($usuario->is_administrador() && \Auth::guard()->user()->is_administrador())
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    <li>
                        <a href="{{ route('admin.create-usuario-asesor') }}">
                            <i class="fa fa-user-plus icon-menu"></i>
                            Crear Asesores
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('institucion.create') }}">
                            <i class="fa fa-plus icon-menu"></i>
                            Crear Institución
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reportes-comentarios') }}">
                            <i class="fa fa-chart-line icon-menu"></i>
                            Ver reportes de comentarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reportes-posts') }}">
                            <i class="fa fa-chart-bar icon-menu"></i>
                            Ver reportes de publicaciones
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @else
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    @if ($usuario->is_estudiante() || $usuario->is_maestro() || $usuario->is_institucion())
                    <li>
                        @if ($usuario->is_estudiante() || $usuario->is_maestro())
                        <a href="{{ route('grupos.grupos-investigacion-usuario', ['investigacion', $usuario->id]) }}">
                            <i class="fa fa-flask icon-menu"></i>
                            Grupos de investigación
                        </a>
                        @elseif ($usuario->is_institucion())
                        <a href="{{ route('grupos.grupos-investigacion-institucion', ['investigacion', $usuario->id]) }}">
                            <i class="fa fa-flask icon-menu"></i>
                            Grupos de investigación
                        </a>
                        @endif
                    </li>
                    <li>
                        @if ($usuario->is_estudiante() || $usuario->is_maestro())
                        <a href="{{ route('grupos.grupos-investigacion-usuario', ['tematica', $usuario->id]) }}">
                            <i class="fa fa-network-wired icon-menu"></i>
                            Redes temáticas
                        </a>
                        @elseif ($usuario->is_institucion())
                        <a href="{{ route('grupos.grupos-investigacion-institucion', ['tematica', $usuario->id]) }}">
                            <i class="fa fa-network-wired icon-menu"></i>
                            Redes temáticas
                        </a>
                        @endif
                    </li>
                    @endif
                    @if ($usuario->is_asesor())
                        <li>
                            <a href="{{ route('usuario.asesorias', $usuario->id) }}">
                                <i class="fa fa-tasks icon-menu"></i>
                                Grupo y redes que asesora
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif
</aside>