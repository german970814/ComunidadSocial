@foreach($form->get_form() as $name => $field)
    <div class="{{ $field['xs'] }} {{ $field['sm'] }}">
        <div class="form-group">
            @include(sprintf('fields.%s', isset($field['type']) ? $field['type'] : 'text'), array_merge(['name' => $name], $field))
        </div>
    </div>
@endforeach