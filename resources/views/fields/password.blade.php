<input
    type="password"
    name="{{ $name }}"
    value="{{ old( $name, isset($value) ? $value : '' ) }}"
    class="form-control {{ $errors->has($name) ? ' border-color-3' : ' border-color-5' }}""
    placeholder="{{ isset($label) ? $label : '' }}" />
@if($errors->has($name))
@foreach ($errors->get($name) as $error)
    <span class="invalid-feedback color-3" role="alert">
        <strong>{{ $error }}</strong>
    </span>
@endforeach
@endif