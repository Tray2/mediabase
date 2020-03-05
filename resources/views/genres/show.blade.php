@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<?php $books = $genre->books; ?>
{{ $genre->genre }}

@include('common.books_list')
@endsection