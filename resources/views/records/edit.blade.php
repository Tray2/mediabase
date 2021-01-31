{!!  $genres !!}
@extends('layouts.app')
@section('content')
    <form action="/records" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id">
        <input type="hidden" name="artist_id">
        <input type="text" name="title" id="title" value="{{ $record->title }}">
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
        <input type="text" name="released" id="released" value="{{ $record->released }}">
        <input type="text" name="barcode" id="barcode" value="{{ $record->barcode }}">
        <input type="text" name="release_code" id="release_code" value="{{ $record->release_code }}">
        <input type="text" name="spine_code" id="spine_code" value="{{ $record->spine_code }}">
        <input type="submit" value="Update">
    </form>
@endsection
