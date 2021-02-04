@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
    @auth
        <a href="/artists" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add record</a>
    @endauth
@include('common.records_list')
@endsection
