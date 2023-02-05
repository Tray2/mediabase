@props([
    'field' => '',
    'placeholder' => '',
    'value' => '',
    'listname' => '',
    'data' => [],
    'suffix' => '_name'
])

<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<input list="{{ $listname }}"
       id="{{ $field }}"
       name="{{ $field . $suffix}}"
       placeholder="{{ $placeholder }}"
       value="{{ old($field . $suffix, $value) }}">
<datalist id="{{ $listname }}">
    @foreach($data as $item)
        <option value="{{ $item->name }}"></option>
    @endforeach
</datalist>
