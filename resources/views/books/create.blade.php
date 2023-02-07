@extends('layouts.master')
@section('main')
    <form action="{{ route('books.store') }}" method="post">
        @csrf
        <x-text-input-mb field="title"
                         placeholder="Title..." />
        <x-text-input-mb field="published_year"
                         placeholder="YYYY" />
        <x-dynamic-datalist-mb field="author"
                               placeholder="Author"
                               listname="authors"
                               :data="$authors"
                               :datacolumns="['last_name', 'first_name']"
                               columnseparator=", "/>
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"/>
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"/>
        <x-text-input-mb field="isbn"
                         placeholder="ISBN..." />
        <x-textarea-mb field="blurb" />
        <x-datalist-mb field="series"
                       placeholder="Series..."
                       listname="series-list"
                       :data="$series"/>
        <x-number-input-mb  field="part"
                            placeholder="Part..."
                            size="3" />
        <x-datalist-mb field="publisher"
                       placeholder="Publisher..."
                       listname="publishers"
                       :data="$publishers"/>
        <x-submit-mb />
    </form>
    <x-validation_errors />
@endsection
