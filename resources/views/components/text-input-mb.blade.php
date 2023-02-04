@props([
    'field' => '',
    'placeholder' => '',
    'value' => ''
])
<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<input type="text"
       name="{{ $field }}"
       id="{{ $field }}"
       placeholder="{{ $placeholder }}"
       value="{{ old($field, $value) }}">
