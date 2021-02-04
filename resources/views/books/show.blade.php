@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<div class="flex">
    <div class="w-3/4 p-5 rounded">
        <div class="flex">
            <div class="w-3/4">
                <div class="flex">
                    <h1 class="text-3xl">{{ $book->title }}</h1>
                    @if($book->series !== 'Standalone')
                    <h3 class="text-lg ml-3 mt-2 align-baseline">
                        Part {{ $book->part }} of {{ $book->series }}
                    </h3>
                    @endif
                </div>
                <h2 class="text-lg ml-2 mt-2">by
                @foreach($book->author as $author)
                    <a href="/authors/{{ $author->slug }}">{{ $author->name }}</a>
                    @if( $loop->index !== $loop->count -1) & @endif
                @endforeach
                </h2>
                @auth()
                        @if($book->inCollection() > 0)
                            <span class="text-base bg-green-600 text-gray-100 py-1 px-2 rounded">Collected</span>
                        @else
                            <form method="POST" action="/bookcollections">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="submit" value="Add" title="Add book to your collection."
                                    class="text-base bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                            </form>
                        @endif
                        @if($book->isRead() > 0)
                            <span class="text-base bg-green-600 text-gray-100 py-1 px-2 rounded">Read</span>
                        @else
                            <form method="POST" action="/books/read">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="submit" value="Mark Read" title="Mark as read."
                                        class="text-base bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                            </form>
                        @endif
                @endauth
            </div>
            <div class="flex">
                <ul class="bg-gray-300 border-1 border-gray-800 rounded shadow p-2">
                    <li>
                        Released: {{ $book->released }}
                    </li>
                    <li>
                        isbn: {{ $book->isbn }}
                    </li>
                    <li>
                        Pages: {{ $book->pages }}
                    </li>
                    <li>
                        Genre: {{ $book->genre->genre }}
                    </li>
                    <li>
                        Format: {{ $book->format->format }}
                    </li>
                    <li>
                            Score: {{ $book->score }}
                    </li>
                </ul>
                @auth
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold ml-2 mb-2 py-2 px-4 rounded self-start" href="/books/{{ $book->id }}/edit">Edit</a>
                @endauth
            </div>
        </div>
        <div class="flex">
            <img class="mt-5" src="{{ Storage::url('covers/books/' . $book->id . '.jpg') }}">
            <p class="mt-5 ml-5 bg-gray-300 p-8 rounded text-lg leading-normal shadow-md">
                {{ $book->blurb }}
            </p>
        </div>
    </div>

    <div class="mt-4">
        @if($book->series != 'Standalone')
        <div class="ml-5 bg-gray-200 p-5 rounded">
            <h3 class="text-base">Other books in the series:</h3>
            <ul class="mt-3">
                @foreach($book->otherInSeries() as $otherBook)
                    <li class="mb-2"><a href="/books/{{ $otherBook->book_id }}">{{ $otherBook->title }}</a> {{ $otherBook->released }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($book->otherBooks()->count() > 0)
        <div class="mt-5 ml-5 bg-gray-200 p-5 rounded">
            <h3 class="mt-5 text-base">Other books by
                @foreach($book->author as $author)
                    <a href="/authors/{{ $author->slug }}">{{ $author->name }}</a>
                    @if( $loop->index !== $loop->count -1) & @endif
                @endforeach
            </h3>
            <ul class="mt-5">
                @foreach($book->otherBooks() as $otherBook)
                    <li class="mb-2"><a href="/books/{{ $otherBook->book_id }}">{{ $otherBook->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
@endsection
