@extends('layouts.master')
@section('main')
    <main>
        @auth()
            <a id="add-game" href="{{ route('games.create') }}">Add Game</a>
        @endauth
        <a href="{{ route('games.index') }}">Show All</a>

        @foreach( $games as $game)
            <a href="{{ route('games.show', $game->id) }}">{{ $game->title }}</a>
            <a href="{{ route('games.index', ['released' => $game->release_year]) }}">{{ $game->release_year }}</a>
            <a href="{{ route('games.index', ['platform' => $game->platform]) }}">{{ $game->platform }}</a>
            <a href="{{ route('games.index', ['genre' => $game->genre]) }}">{{ $game->genre }}</a>
            <a href="{{ route('games.index', ['format' => $game->format]) }}">{{ $game->format }}</a>
        @endforeach
    </main>
@endsection
