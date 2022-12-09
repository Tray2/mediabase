<form action="{{ route('records.update', $record) }}" method="post">
    @csrf
    @method('PUT')
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="{{ old('title', $record->title) }}">
    <label for="release_year">Release Year:</label>
    <input type="text" name="release_year" id="release_year" size="4" value="{{ old('release_year', $record->release_year) }}">
    <label for="barcode">Barcode:</label>
    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $record->barcode) }}">
    <label for="spine_code">Spine Code:</label>
    <input type="text" name="spine_code" id="spine_code" value="{{ old('spine_code', $record->spine_code) }}">
    <label for="country_name">Country:</label>
    <input list="countries" name="country_name" id="country_name" value="{{ old('country_name', $record->country) }}">
    <datalist id="countries">
        @foreach($countries as $country)
            <option value="{{ $country->name }}"></option>
        @endforeach
    </datalist>
    <label for="artist">Artist:</label>
    <input list="artists" name="artist" id="artist" value="{{ old('artist', $record->artist) }}">
    <datalist id="artists">
        @foreach($artists as $artist)
            <option value="{{ $artist->name }}"></option>
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" name="format_name" id="format" value="{{ old('format_name', $record->format) }}">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" name="genre_name" id="genre" value="{{ old('genre_name', $record->genre) }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="record_label">Record Label:</label>
    <input list="record_labels" name="record_label_name" id="record_label" value="{{ old('record_label_name', $record->record_label) }}">
    <datalist id="record_labels">
        @foreach($recordLabels as $recordLabel)
            <option value="{{ $recordLabel->name }}"></option>
        @endforeach
    </datalist>

    @foreach($tracks as $track)
        <label for="track_positions">Position:</label>
        <input type="text" name="track_positions[]" id="track_positions" value="{{ old('track_positions.' . $loop->index, $track->position) }}">
        <label for="track_artists">Artist:</label>
        <input list="artists" name="track_artists[]" id="track_artists" value="{{ old('track_artists.' . $loop->index , $track->artist) }}">
        <label for="track_titles">Title:</label>
        <input type="text" name="track_titles[]" id="track_titles" value="{{ old('track_titles.' . $loop->index, $track->title) }}">
        <label for="track_durations">Duration:</label>
        <input type="text" name="track_durations[]" id="track_durations" value="{{ old('track_durations.' . $loop->index, $track->duration) }}">
        <label for="track_mixes">Mix:</label>
        <input type="text" name="track_mixes[]" id="track_mixes" value="{{ old('track_mixes.' . $loop->index, $track->mix) }}">
    @endforeach

    <input type="submit">
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
