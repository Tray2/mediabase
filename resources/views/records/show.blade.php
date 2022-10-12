{{ $record->artist }}
{{ $record->title }}
{{ $record->release_year }}
{{ $record->format }}
{{ $record->genre }}
{{ $record->spine_code }}
{{ $record->barcode }}
{{ $record->country }}
{{ $record->record_label }}

@if($otherRecords->count() > 0)
    <ul>
        @foreach($otherRecords as $record)
            {{ $record->title }}
        @endforeach
    </ul>
@endif
