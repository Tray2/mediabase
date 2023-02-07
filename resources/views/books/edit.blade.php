@extends('layouts.master')
@section('main')
    <form action="{{ route('books.update', $book) }}" method="post">
        @method('PUT')
        @csrf
        <x-text-input-mb field="title"
                         placeholder="Title..."
                         value="{{ $book->title }}" />
        <x-text-input-mb field="published_year"
                         placeholder="YYYY"
                         size="4"
                         value="{{ $book->published_year }}" />
        <x-dynamic-datalist-mb field="author"
                               placeholder="Author"
                               listname="authors"
                               :data="$authors"
                               :datacolumns="['last_name', 'first_name']"
                               columnseparator=", "
                               :value="explode('&', $book->author_name)" />
        <x-datalist-mb field="format"
                       placeholder="Format..."
                       listname="formats"
                       :data="$formats"
                       value="{{ $book->format }}" />
        <x-datalist-mb field="genre"
                       placeholder="Genre..."
                       listname="genres"
                       :data="$genres"
                       value="{{ $book->genre }}" />
        <x-text-input-mb field="isbn"
                         placeholder="ISBN..."
                         value="{{ $book->isbn }}"/>
        <x-textarea-mb field="blurb"
                       value="{{ $book->blurb }}" />
        <x-datalist-mb field="series"
                       placeholder="Series..."
                       listname="series-list"
                       :data="$series"
                       value="{{ $book->series }}"/>
        <x-number-input-mb  field="part"
                            placeholder="Part..."
                            size="3"
                            value="{{ $book->part }}"/>
        <x-datalist-mb field="publisher"
                       placeholder="Publisher..."
                       listname="publishers"
                       :data="$publishers"
                        value="{{ $book->publisher }}"/>
            <x-submit-mb />
    </form>
    <x-validation_errors />
@endsection
