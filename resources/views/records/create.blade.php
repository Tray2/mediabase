@extends('layouts.app')
@section('content')
    <form action="/records" method="POST">
        @csrf
        <input type="hidden" name="artist_id" value="{{ isset($artist) ? old('artist_id', $artist->id): old('artist_id') }}">
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label for="title" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Title</label>
            <input type="text" name="title" id="title"
                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                value="{{ isset($record) ? old('title', $record->title): old('title') }}"
                required>
        </div>
        <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
            <label for="released" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Relesed</label>
            <input type="text" name="released"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   value="{{ isset($record) ? old('released', $record->released): old('released') }}"
                   required>
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label for="barcode" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Barcode</label>
            <input type="text" name="barcode"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   value="{{ isset($record) ? old('barcode', $record->barcode): old('barcode') }}"
            >
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label for="release_code" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Release Code</label>
            <input type="text" name="release_code"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   value="{{ isset($record) ? old('release_code', $record->release_code): old('release_code') }}"
                   required>
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label for="spine_code" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Spine Code</label>
            <input type="text" name="spine_code"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   value="{{ isset($record) ? old('spine_code', $record->spine_code): old('spine_code') }}"
                   required>
        </div>

        <div class="w-full md:w-2/4 px-3 mb-6 md:mb-0">
            <label for="genre_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Genre</label>
            <div class="relative">
                <select name="genre_id"
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        required>
                    @if(old('genre_id'))
                        <option value="" disabled>Select your genre</option>
                    @else
                        <option value="" disabled {{ isset($record) ? '' : 'selected' }}>Select your genre</option>
                    @endif
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ isset($record) && $record->genre_id == $genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                </div>
            </div>
        </div>
        <div class="w-full md:w-2/4 px-3 mb-6 md:mb-0">
            <label for="format_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Format</label>
            <div class="relative">
                <select name="format_id"
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        required>
                    @if(old('format_id'))
                        <option value="" disabled>Select your format</option>
                    @else
                        <option value="" disabled {{ isset($record) ? '' : 'selected' }}>Select your format</option>
                    @endif
                    @foreach($formats as $format)
                        <option value="{{ $format->id }}" {{ isset($record) && $record->format_id == $format->id ? 'selected' : '' }}>{{ $format->format }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                </div>
            </div>
        </div>
        <div class="flex w-full justify-end">
            <input type="submit" value="Save" class="bg-blue-500  hover:bg-blue-700 text-white font-bold mx-3 mb-2 py-2 px-4 rounded">
        </div>
    </form>
@endsection
