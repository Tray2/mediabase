<form action="{{ route('games.update', $game) }}" method="post">
    @csrf
    @method('PUT');
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="{{ old('title', $game->title) }}">
    <label for="release_year">Release Year:</label>
    <input type="text" name="release_year" id="release_year" value="{{ old('release_year', $game->release_year) }}">
    <label for="blurb">Blurb:</label>
    <textarea name="blurb" id="blurb" >{{ $game->blurb }}</textarea>
    <label for="platform">Platform</label>
    <input list="platforms" name="platform_name" id="platform" value="{{ old('platform_name', $game->platform) }}">
    <datalist id="platforms">
        @foreach($platforms as $platform)
            <option value="{{ $platform->name }}"></option>
        @endforeach
    </datalist>
    <label for="format">Format</label>
    <input list="formats" name="format_name" id="format" value="{{ old('format_name', $game->format) }}">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre</label>
    <input list="genres" name="genre_name" id="genre" value="{{ old('genre_name', $game->genre) }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit" value="Submit">
</form>
<x-validation_errors></x-validation_errors>
