@props([
    'field' => '',
    'cols' => 30,
    'rows' => 10,
    'value' => ''
])

<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<textarea name="{{ $field }}"
          id="{{ $field }}"
          cols="{{ $cols }}"
          rows="{{ $rows }}">
    {{ old($field, $value) }}
</textarea>
