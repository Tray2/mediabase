@extends('layouts.app')
@section('content')
    <h3>{{ $record->artist->name }} - {{ $record->title }}</h3>
    <h5>{{ $record->released }}</h5>
    <ul>
        <li>Genre: {{ $record->genre->genre }}</li>
        <li>Format: {{ $record->format->format }}</li>
        <li>Release Code: {{ $record->release_code }}</li>
        <li>Barcode: {{ $record->barcode }}</li>
    </ul>
    <table>
        @foreach($record->tracks as $track)
            <tr>
                <td>{{ $track->track_no }}</td>
                <td>{{ $track->title }}</td>
                <td>{{ $track->mix }}</td>
            </tr>
        @endforeach
    </table>

@endsection
