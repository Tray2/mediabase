@props([
    'field' => '',
    'placeholder' => '',
    'value' => '',
    'listname' => '',
    'data' => []
])

<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<input list="{{ $listname }}"
       id="{{ $field }}"
       name="{{ $field }}_name"
       placeholder="{{ $placeholder }}"
       value="{{ old($field . '_name', $value) }}">
<datalist id="{{ $listname }}">
    @foreach($data as $item)
        <option value="{{ $item->name }}"></option>
    @endforeach
</datalist>
