@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<form method="POST" action="/genres">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ old('id', $genre->id) }}">
    <input type="text" name="genre" value="{{ isset($genre) ? old('genre', $genre->genre): old('genre') }}" required>
    <select name="media">
        @foreach($mediaTypes as $media)
            <option value="{{ $media->id }}">{{ $media->media }}</option>
        @endforeach
    </select>
    <input type="submit" value="Update">
</form>
@endsection
