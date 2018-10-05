@foreach($form->get_form() as $name => $field)
    <div class="{{ $field['xs'] }} {{ $field['sm'] }}">
        <div class="form-group">
            @include(sprintf('fields.%s', isset($field['type']) ? $field['type'] : 'text'), array_merge(['name' => $name, 'with_label' => isset($with_labels) ? $with_labels : false], $field))
        </div>
    </div>
@endforeach