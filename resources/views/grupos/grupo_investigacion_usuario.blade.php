@extends('usuarios.base_profile')

@section('section')
<section>
    @if ($usuario->is_institucion() && \App\Libraries\Permissions::has_perm('crear_grupo', ['institucion' => $usuario->institucion]))
        @include('layouts.title_page', ['title_page' => $title, 'button' => ['type' => 'link', 'href' => route('grupos.create', [$tipo, $usuario->institucion->id]), 'text' => $tipo == 'tematica' ? 'Crear Red' : 'Crear Grupo']])
    @else
        @include('layouts.title_page', ['title_page' => $title])
    @endif
    <div class="row">
        @foreach ($grupos as $item)
        <div class="col-md-4 col-sm-6 col-xs-12 block">
            @include('grupos.grupo_card')
        </div>
        @endforeach
    </div>
    <div class="row">
        @include('layouts.pagination')
    </div>
</section>
@endsection