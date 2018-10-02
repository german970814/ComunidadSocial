<select name="{{ $name }}" class="form-control border-color-5">
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
    <div>{{ $error }}</div>
@endforeach
@endif
