@extends('layouts.master')
@section('main')

    <form action="{{ route('games.update', $game) }}" method="post">
        @csrf
        @method('PUT')
        <x-text-input-mb field="title"
                         placeholder="Title..."
                         value="{{ $game->title }}"/>
        <x-text-input-mb field="release_year"
                         placeholder="YYYY"
                         value="{{ $game->release_year }}"/>
        <x-textarea-mb field="blurb"
                       value="{{ $game->blurb }}" />
        <x-datalist-mb field="platform"
                       placeholder="Platform..."
                       listname="platforms"
                       :data="$platforms"
                       value="{{ $game->platform }}" />
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"
                        value="{{ $game->format }}"/>
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"
                        value="{{ $game->genre }}"/>
        <x-submit-mb />
    </form>
    <x-validation_errors></x-validation_errors>
@endsection
