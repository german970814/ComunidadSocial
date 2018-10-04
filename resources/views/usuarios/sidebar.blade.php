<aside>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">Información Personal</h3>
            </div>
            <div class="panel-body">
                <div class="media-profile">
                    <div class="photo-profile">
                        <img class="photo-profile-img" width="200" height="200" src="{{ asset('assets/img/user.png') }}" alt="profile" />
                    </div>
                </div>
                <div class="teamInfo teamTeacher">
                    <h3 class="color-3">{{ $usuario->get_full_name() }}</h3>
                    <p>{{ $usuario->tipo_usuario === 'M' ? 'Maestro' : 'Estudiante' }}</p>
                </div>
                <div class="actions">
                    @auth
                        @if (Auth::user()->usuario->id == $usuario->id)
                        <a data-original-title="Editar" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3" href="{{ route('usuario.edit', $usuario->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endif
                        @if (!Auth::user()->usuario->is_amigo($usuario) && !(Auth::user()->usuario->id == $usuario->id))
                        <a data-original-title="Agregar Amigos" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-2 solicitud-amistad">
                            <i class="fa fa-user-plus"></i>
                        </a>
                        @endif
                    @endauth
                    <a class="link-circle bg-color-1" data-original-title="Otra opción" data-placement="bottom" data-toggle="tooltip">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <ul class="list-unstyled categoryItem">
                    <li>
                        <a href="{{ route('usuario.show', $usuario->id) }}">Muro</a>
                    </li>
                    <li>
                        <a href="#">Información</a>
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
                        <a href="#">Mis amigos</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>