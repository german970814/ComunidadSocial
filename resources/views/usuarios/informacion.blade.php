@extends('usuarios.base_profile')

@section('section')
<div class="teachersInfo">
    {{-- <h3>Acerca de mi</h3>
    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Vir ginia, looked up one of the more obscure Latin words, consectetur, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero</p> --}}

    <h3>Información</h3>

    <div class="teachersProfession bg-color-1">Nombre Completo</div>
    <div class="professionDetails">{{ $usuario->get_full_name() }}</div>
    <div class="teachersProfession bg-color-2">Fecha nacimiento</div>
    <div class="professionDetails">{{ $usuario->fecha_nacimiento ? $usuario->fecha_nacimiento : 'NO REGISTRA' }}</div>
    <div class="teachersProfession bg-color-3">Género</div>
    <div class="professionDetails">{{ $usuario->sexo === 'M' ? 'MASCULINO' : 'FEMENINO' }}</div>
    <div class="teachersProfession bg-color-4">Perfil</div>
    <div class="professionDetails">{{ $usuario->tipo_usuario === 'M' ? 'MAESTRO' : 'ESTUDIANTE' }}</div>
    <div class="teachersProfession bg-color-5">Grupo étnico</div>
    <div class="professionDetails">{{ $usuario->grupo_etnico ? $usuario->grupo_etnico : 'NINGUNO' }}</div>
</div>
@endsection