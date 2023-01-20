<form action="{{ route('games.store') }}" method="post">
    @csrf
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title') }}">
    <label for="release_year">Release year</label>
    <input type="text" id="release_year" name="release_year" value="{{ old('release_year') }}">
    <label for="blurb">Blurb</label>
    <textarea name="blurb" id="blurb" value="{{ old('blurb') }}"></textarea>
    <label for="platform">Platform</label>
    <input type="text" list="platforms" id="platform" name="platform_name" value="{{ old('platform_name') }}">
    <label for="format">Format</label>
    <input type="text" list="formats" id="format" name="format_name" value="{{ old('format_name') }}" >
    <label for="genre">Genre</label>
    <input type="text" list="genres" id="genre" name="genre_name" value="{{ old('genre_name') }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <datalist id="platforms">
        @foreach($platforms as $platform)
            <option value="{{ $platform->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit" value="Submit">
</form>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
