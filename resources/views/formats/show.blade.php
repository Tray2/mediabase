@extends('layouts.app')
@section('content')
<?php $books = $format->books; ?>
{{ $format->format }}

@include('common.books_list')
@endsection
