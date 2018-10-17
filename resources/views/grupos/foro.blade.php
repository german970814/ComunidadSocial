@extends('grupos.base')

@section('section')
<section>
    @if (\App\Libraries\Permissions::has_perm('editar_foro', ['foro' => $foro]))
        @include('layouts.title_page', ['title_page' => 'Foro', 'button' => ['type' => 'link', 'href' => route('grupos.editar-foro', $grupo->id), 'text' => 'Editar']])
    @else
        @include('layouts.title_page', ['title_page' => 'Foro'])
    @endif
    <div class="row">
        <h3>{{ $foro->tema }}</h3>
    </div>

    @if ($foro->respuestas->count())
    <div class="row">
        @foreach($foro->respuestas->all() as $respuesta)
        <div class="col-xs-12">
            <div class="well">
                <div id="respuesta-{{ $respuesta->id }}">
                    <div class="media">
                        <div class="media-left">
                            <a href="{{ $respuesta->usuario->get_profile_url() }}">
                                <img src="{{ $respuesta->usuario->get_profile_photo_url() }}" alt="profile" width="32" height="32" class="media-object" />
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="{{ $respuesta->usuario->get_profile_url() }}">
                                <h5 class="media-heading">{{ $respuesta->usuario->get_full_name() }}</h5>
                            </a>
                            <span>{{ $respuesta->created_at }}</span>
                        </div>
                        <div class="row post-content">
                            <div class="post">
                                <p>{{ $respuesta->descripcion }}</p>
                            </div>
                        </div>
                        {{-- <div class="well-actions pull-right">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-warning"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <li class=""><a href="javascript:void(0)" class="reportar-respuesta">Reportar respuesta</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="space-25"></div>
        </div>
        @endforeach
    </div>
    @else
        <div class="row">
            @include('layouts.vacio', ['text' => 'No hay comentarios'])
        </div>
    @endif

    <div class="row">
        @include('grupos.respuesta_foro')
    </div>
</section>
@endsection