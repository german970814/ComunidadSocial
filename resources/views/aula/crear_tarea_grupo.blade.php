@extends('grupos.base')

@section('section')
    <section>
        @include('layouts.title_page', ['title_page' => isset($tarea) ? 'Editar Tarea' : 'Crear Tareas'])
        <div class="row">
            <form action="{{ isset($tarea) ? route('aula.actualizar-tarea', $tarea->id) : route('aula.guardar-tarea-grupo', $grupo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-12 box {{ $errors->has('files.*') ? 'has-error' : '' }}">
                    {{-- <div class="drop-files">
                        <input type="file" id="file" name="files[]" multiple />
                    </div> --}}
                    <div class="box__input">
                        <input class="box__file" type="file" name="files[]" id="file" accept="image/png,image/jpeg,image/jpg,image/gif,image/svg+xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/pdf" data-multiple-caption="{count} files selected" multiple />
                        <label for="file"><strong class="link-choice {{ $errors->has('files.*') ? 'color-3' : 'color-primary' }}">Escoger un archivo</strong><!--<span class="box__dragndrop"> o arrastrar uno aquí</span>.--></label>
                    </div>
                    <div class="box__preview"></div>
                    @if ($errors->has('files.*'))
                        <span class="invalid-feedback color-3" role="alert">
                            <strong>El o los archivos deben ser del formato .png .jpg .jpeg .pdf .doc .docx .ppt o .pttx</strong>
                        </span>
                    @endif
                </div>
                @if (isset($tarea))
                <div class="documentos-container row">
                    @foreach ($tarea->documentos->all() as $documento)
                        @include('aula.documento')
                    @endforeach
                </div>
                @endif
                <div class="col-xs-offset-5 col-xs-2">
                    <button class="btn btn-primary" type="submit">{{ isset($tarea) ? 'Editar' : 'Crear' }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('custom_css')
<style>
.box__dragndrop,
.box__uploading,
.box__success,
.box__error {
  display: none;
}

.box__input .box__file {
    position: absolute;
    right: 0;
    bottom: 0;
    opacity: 0;
    width: 0;
    height: 0;
}

.box__input {
    text-align: center;
    position: relative;
}

.box {
    padding: 50px;
    text-align: center;
}

.box.has-error {
    outline-color: #ea7066 !important;
}

.box .box__preview {
    display: flex;
}

.box .box__img-contatiner {
    display: inline-block;
    padding: 0 10px;
    width: 140px;
    text-align: center;
}

.box .box__filename {
    display: block;
}

.box .box__img-preview {
    width: 90px;
}

.link-choice {
    cursor: pointer;
}

.box.has-advanced-upload {
  background-color: white;
  outline: 2px dashed black;
  outline-offset: -10px;
}

.box.is-dragover {
  background-color: grey;
}

.box.has-advanced-upload .box__dragndrop {
  display: inline;
}

.box.is-uploading .box__input {
  visibility: none;
}

.box.is-uploading .box__uploading {
  display: block;
}

.no-js .box__button {
  display: block;
}
</style>
@endsection

@section('custom_script')
<script>
var isAdvancedUpload = function() {
  var div = document.createElement('div');
  return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

$('input[name="files[]"]').on('change', function () {
    $('.box__preview').html('');
    for (var file of $(this)[0].files) {
        console.log(file.type)
        if (['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/svg+xml'].indexOf(file.type) + 1) {
            $('.box__preview').append(
                $('<div></div>').addClass('box__img-contatiner').append(
                    $('<img />').addClass('box__img-preview').attr('src', '{{ asset("assets/img/svg/picture.svg") }}'),
                    $('<span>').addClass('box__filename').html(file.name)
                )
            );
        } else if (['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].indexOf(file.type) + 1) {
            $('.box__preview').append(
                $('<div></div>').addClass('box__img-contatiner').append(
                    $('<img />').addClass('box__img-preview').attr('src', '{{ asset("assets/img/svg/doc.svg") }}'),
                    $('<span>').addClass('box__filename').html(file.name)
                )
            );
        } else if (['application/vnd.ms-powerpoint'].indexOf(file.type) + 1) {
            $('.box__preview').append(
                $('<div></div>').addClass('box__img-contatiner').append(
                    $('<img />').addClass('box__img-preview').attr('src', '{{ asset("assets/img/svg/ppt.svg") }}'),
                    $('<span>').addClass('box__filename').html(file.name)
                )
            );
        } else if (['application/pdf'].indexOf(file.type) + 1) {
            $('.box__preview').append(
                $('<div></div>').addClass('box__img-contatiner').append(
                    $('<img />').addClass('box__img-preview').attr('src', '{{ asset("assets/img/svg/pdf.svg") }}'),
                    $('<span>').addClass('box__filename').html(file.name)
                )
            );
        } else {
            $('.box__preview').append(
                $('<div></div>').addClass('box__img-contatiner').append(
                    $('<img />').addClass('box__img-preview').attr('src', '{{ asset("assets/img/svg/file.svg") }}'),
                    $('<span>').addClass('box__filename').html(file.name)
                )
            );
        }
    }
})

if (isAdvancedUpload) {
    var $form = $('.box');

    if (isAdvancedUpload) {
        $form.addClass('has-advanced-upload');
        var droppedFiles = false;

        // $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        // })
        // .on('dragover dragenter', function() {
        //     $form.addClass('is-dragover');
        // })
        // .on('dragleave dragend drop', function() {
        //     $form.removeClass('is-dragover');
        // })
        // .on('drop', function(e) {
        //     droppedFiles = e.originalEvent.dataTransfer.files;
        //     $.each(droppedFiles, function(i, file) {
        //         // $('input[name="files[]"]').val(file);
        //     });
        // });
    }
}
</script>
@endsection