@extends('layouts.master')
@section('main')

    {{ $record->artist }}
    {{ $record->title }}
    {{ $record->release_year }}
    {{ $record->format }}
    {{ $record->genre }}
    {{ $record->spine_code }}
    {{ $record->barcode }}
    {{ $record->country }}
    {{ $record->record_label }}

    <h2>Track Listing:</h2>
    <table>
        <tr>
            <th>Track</th>
            @if($record->isVarious())
                <th>Artist</th>
            @endif
            <th>Title</th>
            <th>Duration</th>
            <th>Mix</th>
        </tr>
        @foreach($tracks as $track)
            <tr>
                <td>{{ $track->position }}</td>
                @if($record->isVarious())
                    <td>{{ $track->artist }}</td>
                @endif
                <td>{{ $track->title }}</td>
                <td>{{ $track->duration }}</td>
                <td>{{ $track->mix }}</td>
            </tr>
        @endforeach
    </table>
    @if($otherRecords->count() > 0)
        <ul>
            @foreach($otherRecords as $record)
                {{ $record->title }}
            @endforeach
        </ul>
    @endif
@endsection
