<form action="{{ route('movies.update', $movie ) }}" method="post">
    @csrf
    @method('PUT')
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="{{ $movie->title }}">
    <label for="release_year">Release Year</label>
    <input type="text" name="release_year" id="release_year" value="{{ $movie->release_year }}">
    <label for="runtime">Runtime</label>
    <input type="text" name="runtime" id="runtime" value="{{ $movie->runtime }}">
    <label for="blurb">Blurb</label>
    <textarea name="blurb" id="blurb">{{ $movie->blurb }}</textarea>
    <label for="format">Format</label>
    <input list="formats" name="format_name" id="format" value="{{ $movie->format }}">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Format</label>
    <input list="genres" name="genre_name" id="genre" value="{{ $movie->genre }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit" value="Update">
</form>
