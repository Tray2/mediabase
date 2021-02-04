@extends('layouts.app')
@section('content')
<form method="POST" action="/formats">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $format->id }}">
    <input type="text" name="format" value="{{ $format->format }}" required>
    <label for="media">Media</label>
    <select name="media">
        @foreach($mediaTypes as $media)
            <option value="{{ $media->id }}">{{ $media->media }}</option>
        @endforeach
    </select>
    <input type="submit" value="Update">
</form>
@endsection
