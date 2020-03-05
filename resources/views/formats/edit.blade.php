@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<form method="POST" action="/formats">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $format->id }}">
    <input type="text" name="format" value="{{ $format->format }}" required>
    <input type="submit" value="Update">
</form>
@endsection