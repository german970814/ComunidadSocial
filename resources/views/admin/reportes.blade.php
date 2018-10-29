@extends('usuarios.base_profile')

@section('section')
<section>
    <div class="accordionCommon">
        @foreach($reportes as $key => $reporte)
            <div class="panel-group">
                <div class="panel panel-default">
                    <a href="#collapse-a-{{ $key }}" aria-expanded="false" class="panel-heading accordion-toggle collapsed bg-color-4" data-toggle="collapse">
                        <span>{{ $reporte->get_razon() }}</span>
                        <span class="iconBlock iconTransparent">
                            <i class="fa fa-chevron-up"></i>
                        </span>
                        <span class="separator"></span>
                    </a>
                    <div id="collapse-a-{{ $key }}" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            @if ($reporte->get_tipo() == \App\Models\ReporteComentarioPost::$COMENTARIO)
                            <div>
                                Reportado a: {{ $reporte->comentario->usuario->get_full_name() }}
                            </div>
                            <br>
                            <div>
                                {{ $reporte->comentario->mensaje }}
                            </div>
                            <br>
                            <div style="float: right;">
                                Reportado por: {{ $reporte->usuario->get_full_name() }}
                            </div>
                            @else
                            <div>
                                Reportado a: {{ $reporte->post->autor->get_full_name() }}
                            </div>
                            <br>
                            <div>
                                @if ($reporte->post->photo && $reporte->post->get_photo_url())
                                <div class="row post-media">
                                    <div class="media">
                                        <img src="{{ $reporte->post->get_photo_url() }}" alt="post-photo" class="img-preview img-responsive">
                                    </div>
                                </div>
                                @endif
                                <div class="row post-content">
                                    <div class="post">
                                        <p>{{ $reporte->post->mensaje }}</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div style="float: right;">
                                Reportado por: {{ $reporte->usuario->get_full_name() }}
                            </div>
                            @endif
                            <div>
                                <a class="" href="{{ route('admin.reportes-inactivar', $reporte->id) }}">
                                    <span class="label label-warning">Inactivar</span>
                                </a>
                                <a class="" href="{{ route('admin.reportes-eliminar', $reporte->id) }}">
                                    <span class="label label-info">Ignorar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>    
</section>    
@endsection