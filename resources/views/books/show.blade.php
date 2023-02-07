@extends('layouts.master')
@section('main')

    {{ $book->author_name }} - {{ $book->title }}
    {{ $book->isbn }}
    {{ $book->genre }}
    {{ $book->format }}
    {{ $book->published_year }}
    {{ $book->publisher }}
    {{ $book->blurb }}
    {{ $book->part }}
    {{ $book->series }}

    @if($booksInSeries->count() > 0)
        <h2>Books In Series</h2>
        <ul>
            @foreach($booksInSeries as $bookInSeries)
                <li>{{ $bookInSeries->title}}</li>
            @endforeach
        </ul>
    @endif

    @if($otherBooks->count() > 0)
        <h2>Other Books By {{ $book->author_name}}:</h2>
        <ul>
            @foreach($otherBooks as $otherBook)
                <li>{{ $otherBook->title }}</li>
            @endforeach
        </ul>
    @endif
@endsection
