@foreach($form as $name => $field)
    <div class="col-xs-12 col-sm-6">
        <div class="form-group">
            @include(sprintf('fields.%s', isset($field['type']) ? $field['type'] : 'text'), array_merge(['name' => $name], $field))
        </div>
    </div>
@endforeach