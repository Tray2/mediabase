<form action="{{route('movies.store')}}" method="post">
    @csrf
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title') }}">
    <label for="release_year">Release Year</label>
    <input type="text" name="release_year" id="release_year" value="{{ old('release_year') }}">
    <label for="runtime">Runtime</label>
    <input type="text" name="runtime" id="runtime" value="{{ old('runtime') }}">
    <label for="format">Format</label>
    <input list="formats" name="format_name" id="format" value="{{ old('format_name') }}">
    <label for="genre">Genre</label>
    <input list="genres" name="genre_name" id="genre" value="{{ old('genre_name') }}">
    <label for="blurb">Blurb</label>
    <textarea name="blurb" id="blurb">{{ old('blurb') }}</textarea>
    <label for="actor">Actors</label>
    <button title="Add Actor">+</button>
    <input list="actors" name="actor[]" id="actor" value="{{ old('actor.0') }}">
    @if(old('actor'))
        @foreach(old('actor') as $actor)
            @if($loop->index > 0)
                <input list="actors" id="actor" name="actor[]" placeholder="Actor..." value="{{ old('actor')[$loop->index] }}">
            @endif
        @endforeach
    @endif
    <datalist id="actors">
        @foreach($actors as $actor)
            <option value="{{ $actor->first_name . ' ' . $actor->last_name }}"></option>
        @endforeach
    </datalist>
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
<x-validation_errors></x-validation_errors>
