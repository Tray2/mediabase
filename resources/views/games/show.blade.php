@extends('layouts.master')
@section('main')

    {{ $game->title }}
    {{ $game->released_year }}
    {{ $game->format }}
    {{ $game->genre }}
    {{ $game->platform }}
    {{ $game->blurb }}
@endsection
