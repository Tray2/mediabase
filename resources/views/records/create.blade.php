<form action="{{ route('records.store') }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title">
    <label for="released">Released:</label>
    <input type="text" name="released" id="released" size="4">
    <label for="artist">Artist:</label>
    <input list="artists" name="artist" id="artist">
    <datalist id="artists">
        @foreach($artists as $artist)
            <option value="{{ $artist->name }}"></option>
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" name="format_name" id="format">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" name="genre_name" id="genre">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="record_label">Record Label:</label>
    <input list="record_labels" name="record_label_name" id="record_label">
    <datalist id="record_labels">
        @foreach($recordLabels as $recordLabel)
            <option value="{{ $recordLabel->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit">
</form>
