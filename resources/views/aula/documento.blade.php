<div class="col-sm-12 col-md-3 text-center">
    <div style="position: relative;">
        <img class="icon-file" src="{{ $documento->get_icon() }}" />
        @if (\App\Libraries\Permissions::has_perm('editar_tarea', ['tarea' => $tarea]))
            <span data-original-title="Eliminar documento" data-placement="bottom" data-toggle="tooltip" class="document-remove badge bg-color-3"><a style="color: white;" href="{{ $documento->get_eliminar_url() }}"><i class="fa fa-times"></i></a></span>
        @endif
    </div>
    <span data-original-title="Descargar" data-placement="bottom" data-toggle="tooltip" style="display: block;">
        <a class="color-primary" href="{{ $documento->get_url() }}" target="_blank">
            <strong>{{ $documento->get_nombre() }}</strong>
        </a>
    </span>
</div>
