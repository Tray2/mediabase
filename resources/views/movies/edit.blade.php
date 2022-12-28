<form action="{{ route('movies.update', $movie ) }}" method="post">
    @csrf
    @method('PUT')
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}">
    <label for="release_year">Release Year</label>
    <input type="text" name="release_year" id="release_year" value="{{ old('release_year', $movie->release_year) }}">
    <label for="runtime">Runtime</label>
    <input type="text" name="runtime" id="runtime" value="{{ old('runtime', $movie->runtime) }}">
    <label for="blurb">Blurb</label>
    <textarea name="blurb" id="blurb">{{ old('blurb', $movie->blurb) }}</textarea>
    <label for="actor">Actors</label>
    <button title="Add Actor">+</button>
    @if(old('actor'))
        @foreach(old('actor') as $actor)
            <input list="actors" id="actor" name="actor[]" placeholder="Actor..." value="{{ old('actor')[$loop->index] }}">
        @endforeach
    @else
        @foreach(explode(' & ', $movie->actor_name) as $actor)
            <label for="actor">Actor:</label>
            <button title="Remove Actor">X</button>
            <input list="actors" value="{{ old('actor.0', $actor) }}"
                   @if ($loop->first)
                       id="actor"
                   @endif
                   name="actor[]" placeholder="Actor...">
        @endforeach
    @endif
    <datalist id="actors">
        @foreach($actors as $actor)
            <option value="{{ $actor->first_name . ' ' . $actor->last_name }}"></option>
        @endforeach
    </datalist>
    <label for="format">Format</label>
    <input list="formats" name="format_name" id="format" value="{{ old('format_name', $movie->format) }}">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre</label>
    <input list="genres" name="genre_name" id="genre" value="{{ old('genre_name', $movie->genre) }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit" value="Update">
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
