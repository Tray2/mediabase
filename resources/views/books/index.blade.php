@extends('layouts.master')
@section('main')
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
                <td>{{ $book->author_name }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->published_year }}</td>
                <td>{{ $book->series }}</td>
                <td>{{ $book->part }}</td>
                <td>{{ $book->genre }}</td>
                <td>{{ $book->format }}</td>
            </tr>
        @endforeach
    </table>
@endsection
