@extends('layouts.master')
@section('main')
    <main>
        @auth()
            <a id="add-movie" href="{{ route('movies.create') }}">Add Movie</a>
        @endauth
        <a href="{{ route('movies.index') }}">Show All</a>

        @foreach($movies as $movie)
                <a href="{{ route('movies.show', $movie->id) }}">{{ $movie->title }}</a>
                <a href="{{ route('movies.index', ['released' => $movie->release_year]) }}">{{ $movie->release_year }}</a>
            {{ $movie->runtime }}
                <a href="{{ route('movies.index', ['genre' => $movie->genre]) }}">{{ $movie->genre }}</a>
                <a href="{{ route('movies.index', ['format' => $movie->format]) }}">{{ $movie->format }}</a>
        @endforeach
    </main>
@endsection
