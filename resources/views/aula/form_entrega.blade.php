<div class="col-xs-12">
    @include('layouts.form', ['with_labels' => true])
    <div class="col-xs-12 col-sm-12">
        <input type="file" name="file" id="file" />
        @foreach($errors->get('file') as $error)
            <span class="invalid-feedback color-3" role="alert">
                <strong>{{ $error }}</strong>
            </span>
        @endforeach
        <div class="space-25"></div>
        <br />
    </div>
</div>