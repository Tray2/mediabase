@extends('layouts.master')
@section('main')
    <main>
        @auth()
            <a id="add-book" href="{{ route('books.create') }}">Add Book</a>
        @endauth
            <a href="{{ route('books.index') }}">Show All</a>
        <table>
            <tr>
                <th>Author:</th>
                <th>Title:</th>
                <th>Published:</th>
                <th>Series:</th>
                <th>Part:</th>
                <th>Genre:</th>
                <th>Format:</th>
            </tr>
            @foreach($books as $book)
                <tr>
                    <td>
                        <a href="{{ Str::replace('%2C', ',', route('books.index', ['authors' => $book->author_id]))}}">
                            {{ $book->author_name }}
                        </a>
                    </td>
                    <td><a href="{{ route('books.show', $book->book_id) }}">{{ $book->title }}</a></td>
                    <td><a href="{{ route('books.index', ['published' => $book->published_year]) }}">{{ $book->published_year }}</a></td>
                    <td>{{ $book->series }}</td>
                    <td>{{ $book->part }}</td>
                    <td><a href="{{ route('books.index', ['genre' => $book->genre]) }}">{{ $book->genre }}</a></td>
                    <td><a href="{{ route('books.index', ['format' => $book->format]) }}">{{ $book->format }}</a></td>
                </tr>
            @endforeach
        </table>
    </main>
@endsection
