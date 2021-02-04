@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
@auth
    <a href="/authors" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add book</a>
@endauth
@include('common.books_list')
@endsection
