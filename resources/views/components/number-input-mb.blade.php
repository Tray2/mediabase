@props([
    'field'  => '',
    'placeholder' => '',
    'size' => '',
    'value' => ''
])

<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<input type="number"
       id="{{ $field }}"
       name="{{ $field }}"
       placeholder="{{ $placeholder }}"
       size="{{ $size }}"
       value="{{ old($field, $value) }}">
