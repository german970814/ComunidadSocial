@foreach($errors->all() as $error)
    <div class="alert alert-danger fade in alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <strong>Error!</strong>{{ $error }}
    </div>
@endforeach
