@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<?php $books = $format->books; ?>
{{ $format->format }}

@include('common.books_list')
@endsection