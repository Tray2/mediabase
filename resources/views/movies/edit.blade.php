@extends('layouts.master')
@section('main')

    <form action="{{ route('movies.update', $movie ) }}" method="post">
        @csrf
        @method('PUT')
        <x-text-input-mb field="title"
                         placeholder="Title..."
                         value="{{ $movie->title }}"/>
        <x-text-input-mb field="release_year"
                         placeholder="YYYY"
                         value="{{ $movie->release_year }}"/>
        <x-text-input-mb field="runtime"
                         value="{{ $movie->runtime }}"/>
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"
                       value="{{ $movie->format }}"/>
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"
                       value="{{ $movie->genre }}"/>
        <x-textarea-mb field="blurb"
                       value="{{ $movie->blurb }}"/>
        <x-dynamic-datalist-mb field="actor"
                               placeholder="Actor"
                               listname="actors"
                               :data="$actors"
                               :datacolumns="['first_name', 'last_name']"
                               columnseparator=" "
                               :value="explode('&', $movie->actor_name)"/>
        <x-submit-mb/>
    </form>
    <x-validation_errors></x-validation_errors>
@endsection
