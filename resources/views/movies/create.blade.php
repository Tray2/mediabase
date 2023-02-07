@extends('layouts.master')
@section('main')

    <form action="{{route('movies.store')}}" method="post">
        @csrf
        <x-text-input-mb field="title"
                         placeholder="Title..." />
        <x-text-input-mb field="release_year"
                         placeholder="YYYY" />
        <x-text-input-mb field="runtime" />
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"/>
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"/>
        <x-textarea-mb field="blurb" />
        <x-dynamic-datalist-mb field="actor"
                               placeholder="Actor"
                               listname="actors"
                               :data="$actors"
                               :datacolumns="['first_name', 'last_name']"
                               columnseparator=" "/>
        <x-submit-mb />
    </form>
    <x-validation_errors></x-validation_errors>
@endsection
