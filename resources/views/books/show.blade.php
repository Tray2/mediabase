{{ $book->author_name }} - {{ $book->title }}
{{ $book->isbn }}
{{ $book->genre }}
{{ $book->format }}
{{ $book->published_year }}
{{ $book->publisher }}
{{ $book->blurb }}
{{ $book->part }}
{{ $book->series }}

@if($books_in_series->count() > 0)
    <h2>Books In Series</h2>
    <ul>
        @foreach($books_in_series as $books_in_sery)
            <li>{{ $books_in_sery->title}}</li>
        @endforeach
    </ul>
@endif

<h2>Other Books By {{ $book->author_name}}:</h2>
<ul>
    @foreach($other_books as $otherBook)
        <li>{{ $otherBook->title }}</li>
    @endforeach
</ul>
