@extends('layouts.master')
@section('main')
    <main>

        @auth()
            <a id="add-record" href="{{ route('records.create') }}">Add Record</a>
        @endauth
        <a href="{{ route('records.index') }}">Show All</a>

        <table>
            <tr>
                <th>Artist</th>
                <th>Title</th>
                <th>Released</th>
                <th>Genre</th>
                <th>Format</th>
            </tr>

            @foreach($records as $record)
                <tr>
                    <td><a href="{{ route('records.index', ['artist' => $record->artist]) }}">{{ $record->artist }}</td>
                    <td><a href="{{ route('records.show', $record->record_id) }}">{{ $record->title }}</a></td>
                    <td>
                        <a href="{{ route('records.index', ['released' => $record->release_year]) }}">{{ $record->release_year }}
                    </td>
                    <td>
                        <a href="{{ route('records.index', ['genre' => $record->genre_name]) }}">{{ $record->genre_name }}
                    </td>
                    <td>
                        <a href="{{ route('records.index', ['format' => $record->format_name]) }}">{{ $record->format_name }}
                    </td>
                </tr>
            @endforeach
        </table>
    </main>
@endsection
