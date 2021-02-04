@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<h2>{{ $author->name }}
    @auth
        <a href="/books/create?author_id={{ $author->id }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add book</a>
        <a href="/authors/{{ $author->id }}/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    @endauth
</h2>

@if(count($author->books) == 0)
    <h3>Author has no books.</h3>
@else
    @include('common.books_list')
@endif
@endsection
