@extends('layouts.app')
@include('common.book_subnav')
@section('content')
<div>
    <form method="POST" action="/genres" class="w-3/4">
        @csrf
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label for="genre" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Genre</label>
            <input type="text" name="genre" value="{{ isset($genre) ? old('genre', $genre->genre): old('genre') }}"
            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
            required
            >
            <label for="media">Genre Type</label>
            <select name="media">
                @foreach($mediaTypes as $media)Genr
                    <option value="{{ $media->id }}">{{ $media->media }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex w-1/2 justify-end">
            <input type="submit" value="Save" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mx-3 mb-2 py-2 px-4 rounded">
        </div>
    </form>
</div>
@endsection
