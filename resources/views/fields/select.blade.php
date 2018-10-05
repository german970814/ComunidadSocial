@if (isset($with_label) && $with_label)
<label for="id-{{ $name }}">{{ $label }}</label>
@endif
<select id="id-{{ $name }}" name="{{ $name }}" class="form-control {{ $errors->has($name) ? ' border-color-3' : ' border-color-5' }}"">
    <option>{{ isset($label) ? $label : '' }}</option>
    @foreach($options as $option => $lab)
        @if ($option == old( $name, isset($value) ? $value : '' ))
            <option selected value="{{ $option }}">{{ $lab }}</option>
        @else
            <option value="{{ $option }}">{{ $lab }}</option>
        @endif
    @endforeach
</select>
@if($errors->has($name))
@foreach ($errors->get($name) as $error)
    <span class="invalid-feedback color-3" role="alert">
        <strong>{{ $error }}</strong>
    </span>
@endforeach
@endif
