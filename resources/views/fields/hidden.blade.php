<input
    type="hidden"
    id="id-{{ $name }}"
    name="{{ $name }}"
    value="{{ old( $name, isset($value) ? $value : '' ) }}" />