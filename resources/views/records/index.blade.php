@extends('layouts.app')
@include('common.records_subnav')
@section('content')
    @auth
        <a href="/artists" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Record</a>
    @endauth
    <ul>
        @foreach($records as $record)
            <li>{{ $record->title }} {{ $record->rating? $record->rating . '/5.0' : 'Not rated' }}</li>
        @endforeach
    </ul>
@endsection
