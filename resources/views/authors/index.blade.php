@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
@if (session('error'))
<div class="flex justify-center">
    <div class="m-5 w-1/3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
    {{ session('error') }}
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
</div>
@endif
@auth
    <a href="/authors/create" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mb-2 py-2 px-4 rounded">Add author</a>
@endauth
@if(count($authors) == 0)
    <h3>No authors found</h3>
@else
    <table class="mt-6 w-1/3">
        <tr class="text-left bg-gray-500 text-xl"><th class="py-2 pl-2">Author</th><th>Books</th></tr>
        @foreach($authors as $author)
            <tr class="border-b-2 text-lg text-gray-800"><td class="pl-2 py-2"><a href="{{ route('authors.show', $author->id )}}" class="hover:underline">{{ $author->name }}</a></td><td>{{ $author->books_count }}</td></tr>
        @endforeach
    </table>
@endif
@endsection
