@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
{{ $genre->genre }}
@if($type == 'BOOKS')
    @php
        $books = $genre->books;
    @endphp
    @include('common.books_list')
@elseif($type == 'RECORDS')
    @php
        $records = $genre->records;
    @endphp
    @include('common.records_list')
@endif
@endsection
