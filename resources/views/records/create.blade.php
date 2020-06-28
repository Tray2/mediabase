@extends('layouts.app')
@section('content')
    <form action="/records" method="POST">
        @csrf
        <input type="hidden" name="artist_id" value="{{ isset($artist) ? old('artist_id', $artist->id): old('artist_id') }}">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="genre_id">Genre</label>
        <select name="genre_id" id="genre_id">
            @foreach($genres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->genre }}</option>
            @endforeach
        </select>
        <label for="format_id">Format</label>
        <select name="format_id" id="format_id">
            @foreach($formats as $format)
                <option value="{{ $format->id }}">{{ $format->format }}</option>
            @endforeach
        </select>
        <label for="released">Released</label>
        <input type="text" name="released" id="released">
        <label for="barcode">Barcode</label>
        <input type="text" name="barcode" id="barcode">
        <label for="release_code">Release Code</label>
        <input type="text" name="release_code" id="release_code">
        <label for="spine_code">Spine Code</label>
        <input type="text" name="spine_code" id="spine_code">
        <input type="submit" value="Save">
    </form>
@endsection
