@extends('layouts.app')
@section('content')
    @include('common.records_subnav')
    {{ $artist->name }}
        @auth
            <a href="/recordss/create?artist_id={{ $artist->id }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add record</a>
            <a href="/artists/{{ $artist->id }}/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        @endauth
    @if($records->count() == 0)
        <h3>Artist has no records</h3>
    @else
    @endif
@endsection
