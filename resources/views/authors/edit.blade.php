@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <form method="POST" action="/authors">
        <div class="flex flex-wrap -mx-3 mt-6">
            @method('PUT')
            <input type="hidden" name="id" value="{{ old('id', $author->id) }}">
            @include('authors.form')
            <div class="flex w-full justify-end">
                <input type="submit" value="Update" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            </div>
        </div>
    </form>
</div>
@endsection