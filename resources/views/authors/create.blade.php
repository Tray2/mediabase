@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
    <form method="POST" action="/authors">
        <div class="flex flex-wrap -mx-3 mt-6">
            @include('authors.form')
            <div class="flex w-full justify-end">
                <input type="submit" value="Save" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mx-3 mb-2 py-2 px-4 rounded">
            </div>
        </div>
    </form>
</div>
@endsection
