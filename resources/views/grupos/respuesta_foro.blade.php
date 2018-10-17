<form action="{{ route('grupos.guardar-respuesta-foro', $foro->id) }}" method="POST">
    @csrf
    <div class="form-group" style="position: relative">
        <textarea
            rows="3"
            cols="30"
            id="descripcion"
            name="descripcion"
            placeholder="Comentar algo..."
            data-emojiable="true"
            data-emoji-input="unicode"
            class="form-control border-color-1"
        ></textarea>
    </div>
    <div class="pull-right">
        <button class="btn btn-primary">
            Comentar
        </button>
    </div>
</form>

@section('custom_css')
@include('layouts.emojis_styles')
@endsection

@section('custom_script')
@include('layouts.emojis')
@endsection