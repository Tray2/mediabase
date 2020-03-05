@extends('layouts.app')
@section('content')
    <form action="/records" method="POST">
        @csrf
        <input type="hidden" name="artist_id">
        <input type="text" name="title" id="title">
        <select name="genre_id" id="genre_id">
            @foreach($genres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->genre }}</option>
            @endforeach
        </select>
        <select name="format_id" id="format_id"></select>
        <input type="text" name="released" id="released">
        <input type="text" name="barcode" id="barcode">
        <input type="text" name="release_code" id="release_code">
        <input type="text" name="spine_code" id="spine_code">
        <input type="submit" value="Save">
    </form>
@endsection
