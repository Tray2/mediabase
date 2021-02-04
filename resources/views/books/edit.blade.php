@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<form method="post" action="/books/{{ $book->id }}">
    @method('PUT')
    <input type="hidden" name="id" value="{{ $book->id }}">
    @include('books.form')
    <input type="submit" value="Update">
</form>
@endsection
