<form action="{{route('movies.store')}}" method="post">
    @csrf
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <label for="release_year">Release Year</label>
    <input type="text" name="release_year" id="release_year">
    <label for="runtime">Runtime</label>
    <input type="text" name="runtime" id="runtime">
    <label for="blurb">Blurb</label>
    <label for="format">Format</label>
    <input list="formats" name="format_name" id="format">
    <label for="genre">Genre</label>
    <input list="genres" name="genre_name" id="genre">
    <textarea name="blurb" id="blurb"></textarea>
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit" value="Save">
</form>
