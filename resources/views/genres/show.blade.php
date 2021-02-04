@extends('layouts.app')
@include('common.' . strtolower($type) . '_subnav')
@section('content')
<?php $books = $genre->books; ?>
{{ $genre->genre }}

@include('common.books_list')
@endsection
