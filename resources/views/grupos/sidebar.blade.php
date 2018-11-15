<aside>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">{{ $grupo->tipo == \App\Models\GrupoInvestigacion::$TEMATICA ? 'Red temática' : 'Grupo Investigación' }}</h3>
            </div>
            <div class="panel-body">
                {{-- @include('usuarios.profile_photo') --}}
                <div class="teamInfo teamTeacher">
                    <h3 class="color-3">{{ $grupo->get_nombre() }}</h3>
                    <p>{{ $grupo->descripcion }}</p>
                </div>
                <div class="actions">
                    @if (Auth::user()->usuario->espera_respuesta_grupo($grupo))
                        <a href="{{ route('grupos.solicitar', $grupo->id) }}" data-original-title="Cancelar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-3">
                            <i class="fa fa-times"></i>
                        </a>
                    @elseif (Auth::user()->usuario->puede_unirse_grupo($grupo))
                        <a href="{{ route('grupos.solicitar', $grupo->id) }}" data-original-title="Enviar solicitud" data-placement="bottom" data-toggle="tooltip" class="link-circle bg-color-2">
                            <i class="fa fa-user-plus"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    @if (\App\Libraries\Permissions::has_perm('ver_solicitudes_grupo', ['grupo' => $grupo]))
                    <li>
                        <a href="{{ route('grupos.solicitudes', $grupo->id) }}">Solicitudes</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('grupos.show', $grupo->id) }}">Muro</a>
                    </li>
                    <li>
                        <a href="{{ route('usuario.show', $grupo->institucion->usuario->id) }}">Institución</a>
                    </li>
                    <li>
                        <a href="{{ route('grupos.integrantes', $grupo->id) }}">Integrantes</a>
                    </li>
                    @if (\App\Libraries\Permissions::has_perm('administrador'))
                    <li>
                        <a href="{{ route('admin.asignar-asesor-grupo', $grupo->id) }}">Asignar asesor</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @if (\App\Libraries\Permissions::has_perm('ver_tareas', ['grupo' => $grupo]) || \App\Libraries\Permissions::has_perm('ver_examenes', ['grupo' => $grupo]) || \App\Libraries\Permissions::has_perm('ver_foros', ['grupo' => $grupo]))
    <div class="rightSidebar">
        <div class="panel panel-default courseSidebar">
            <div class="panel-body">
                <ul class="list-unstyled categoryItem">
                    @if (\App\Libraries\Permissions::has_perm('ver_tareas', ['grupo' => $grupo]))
                    <li>
                        <a href="{{ route('aula.tareas-grupo', $grupo->id) }}">Tareas</a>
                    </li>
                    @endif
                    @if (\App\Libraries\Permissions::has_perm('ver_examenes', ['grupo' => $grupo]))
                    <li>
                        <a href="{{ route('aula.examenes-grupo', $grupo->id) }}">Exámenes</a>
                    </li>
                    @endif
                    @if (\App\Libraries\Permissions::has_perm('ver_foros', ['grupo' => $grupo]))
                    <li>
                        <a href="{{ route('grupos.ver-foros', $grupo->id) }}">Foros</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif
</aside>