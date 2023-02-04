@props([
    'field' => '',
    'placeholder' => '',
    'value' => [],
    'listname' => '',
    'data' => [],
    'datacolumns' => [],
    'columnseparator' => ''
])
<label for="{{ $field }}">{{ Str::headline($field) }}:</label>
<button title="Add {{ Str::headline($field) }}">+</button>
@forelse(old($field, $value) as $item)
    <input list="{{ $listname }}"
       @if($loop->first)
           id="{{ $field }}"
       @endif
       name="{{ $field }}[]"
       placeholder="{{ $placeholder }}"
       value="{{ old($field[$loop->index], $item) }}"
    >
    <button title="Remove Author">X</button>
@empty
    <input list="{{ $listname }}"
       id="{{ $field }}"
       name="{{ $field }}[]"
       placeholder="{{ $placeholder }}">
@endforelse
<datalist id="{{ $listname }}">
@foreach($data as $item)
    <option value="{{ $item[$datacolumns[0]] . $columnseparator . $item[$datacolumns[1]] }}">
@endforeach
</datalist>
