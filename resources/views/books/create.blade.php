<form action="{{ route('books.store') }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" placeholder="Title..." value="{{ old('title') }}">
    <label for="published_year">Published:</label>
    <input type="text" id="published_year" name="published_year" placeholder="YYYY" size="4"
           value="{{ old('published_year') }}">
    <label for="author">Author:</label>
    <button title="Add Author">+</button>
    <input list="authors" id="author" name="author[]" placeholder="Author..." value="{{ old('author.0') }}">
    @if(old('author'))
        @foreach(old('author') as $author)
            @if($loop->index > 0)
                <input list="authors" id="author" name="author[]" placeholder="Author..." value="{{ old('author')[$loop->index] }}">
            @endif
        @endforeach
    @endif
    <datalist id="authors">
        @foreach($authors as $author)
            <option value="{{ $author->last_name }}, {{ $author->first_name }}">
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" id="format" name="format_name" placeholder="Format..." value="{{ old('format_name') }}">˛˛
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" id="genre" name="genre_name" placeholder="Genre..." value="{{ old('genre_name') }}">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn" placeholder="ISBN..." value="{{ old('isbn') }}">
    <label for="blurb">Blurb:</label>
    <textarea name="blurb" id="blurb" cols="30" rows="10">{{ old('blurb') }}</textarea>
    <label for="series">Series:</label>
    <input list="series-list" id="series" name="series_name" placeholder="Series..." value="{{ old('series_name') }}">
    <datalist id="series-list">
        @foreach($series as $item)
            <option value="{{ $item->name }}"></option>
        @endforeach
    </datalist>
    <label for="part">part:</label>
    <input type="number" id="part" name="part" placeholder="Part..." size="3" value="{{ old('part') }}">
    <label for="publisher">Publisher:</label>
    <input list="publishers" id="publisher" name="publisher_name" placeholder="Publisher..." value="{{ old('publisher_name') }}">
    <datalist id="publishers">
        @foreach($publishers as $publisher)
            <option value="{{ $publisher->name }}"></option>
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


