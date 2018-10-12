@extends('grupos.base')

@section('section')
    <section>
        @include('layouts.title_page', ['title_page' => 'Crear Tareas'])
        <div class="row">
            <form action="{{ route('aula.guardar-tarea-grupo', $grupo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-xs-12">
                    @include('layouts.form', ['with_labels' => true])
                </div>
                <div class="col-xs-12">
                    <div class="drop-files">
                        <input type="file" id="file" name="files[]" multiple />
                    </div>
                </div>
                <div class="col-xs-offset-5 col-xs-2">
                    <button class="btn btn-primary" type="submit">Crear</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('custom_css')
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}" /> --}}
@endsection

@section('custom_script')
{{-- <script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.drop-files').dropzone({
            autoProcessQueue: false, url: '/', autoQueue: false,
            clickable: "#file"
        });
    })
</script> --}}
@endsection