@extends('layouts.master')
@section('main')

    {{ $movie->title }}
    {{ $movie->release_year }}
    {{ $movie->runtime }}
    {{ $movie->blurb }}
    {{ $movie->format }}
    {{ $movie->genre }}

    @foreach($actors as $actor)
        {{ $actor->full_name }}
    @endforeach
@endsection
