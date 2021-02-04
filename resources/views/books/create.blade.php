@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<div>
    <form method="POST" action="/books" class="w-3/4">
        <div class="flex flex-wrap -mx-3 mt-6">
            @include('books.form')
            <label for="read">Read</label>
            <input type="checkbox" name="read" id="read">
            <div class="flex w-full justify-end">
                <input type="submit" value="Save" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mx-3 mb-2 py-2 px-4 rounded">
            </div>
        </div>
    </form>
</div>
@endsection
