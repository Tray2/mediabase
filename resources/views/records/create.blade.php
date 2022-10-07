<form action="{{ route('records.store') }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="{{ old('title') }}">
    <label for="release_year">Release Year:</label>
    <input type="text" name="release_year" id="release_year" size="4" value="{{ old('release_year') }}">
    <label for="barcode">Barcode:</label>
    <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}">
    <label for="spine_code">Spine Code:</label>
    <input type="text" name="spine_code" id="spine_code" value="{{ old('spine_code') }}">
    <label for="country_name">Country:</label>
    <input list="countries" name="country_name" id="country_name" value="{{ old('country_name') }}">
    <datalist id="countries">
        @foreach($countries as $country)
            <option value="{{ $country->name }}"></option>
        @endforeach
    </datalist>
    <label for="artist">Artist:</label>
    <input list="artists" name="artist" id="artist" value="{{ old('artist') }}">
    <datalist id="artists">
        @foreach($artists as $artist)
            <option value="{{ $artist->name }}"></option>
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" name="format_name" id="format" value="{{ old('format_name') }}">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" name="genre_name" id="genre" value="{{ old('genre_name') }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="record_label">Record Label:</label>
    <input list="record_labels" name="record_label_name" id="record_label" value="{{ old('record_label_name') }}">
    <datalist id="record_labels">
        @foreach($recordLabels as $recordLabel)
            <option value="{{ $recordLabel->name }}"></option>
        @endforeach
    </datalist>
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
