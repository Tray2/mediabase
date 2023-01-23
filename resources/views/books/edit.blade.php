<form action="{{ route('books.update', $book) }}" method="post">
    @method('PUT')
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" placeholder="Title..." value="{{ old('title', $book->title) }}"/>
    <label for="published_year">Published:</label>
    <input type="text" id="published_year" name="published_year" placeholder="YYYY" size="4" value="{{ old('published_year', $book->published_year) }}">
    <button title="Add Author">+</button>
    @if(old('author'))
        @foreach(old('author') as $author)
            <input list="authors" id="author" name="author[]" placeholder="Author..." value="{{ old('author')[$loop->index] }}">
        @endforeach
    @else
        @foreach(explode(' & ', $book->author_name) as $author)
            <label for="author">Author:</label>
            <button title="Remove Author">X</button>
            <input list="authors" value="{{ old('author.0', $author) }}"
                   @if ($loop->first)
                       id="author"
                   @endif
                   name="author[]" placeholder="Author...">
        @endforeach
    @endif
    <datalist id="authors">
        @foreach($authors as $author)
            <option value="{{ $author->last_name }}, {{ $author->first_name }}">
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" value="{{ old('format_name', $book->format) }}" id="format" name="format_name" placeholder="Format...">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" value="{{ old('genre_name', $book->genre) }}" id="genre" name="genre_name" placeholder="Genre...">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn" placeholder="ISBN..." value="{{ old('isbn', $book->isbn) }}">
    <label for="blurb">Blurb:</label>
    <textarea name="blurb" id="blurb" cols="30" rows="10">{{ old('blurb', $book->blurb) }}</textarea>
    <label for="series">Series:</label>
    <input list="series-list" value="{{ old('series_name', $book->series) }}" id="series" name="series_name" placeholder="Series...">
    <datalist id="series-list">
        @foreach($series as $item)
            <option value="{{ $item->name }}"></option>
        @endforeach
    </datalist>
    <label for="part">part:</label>
    <input type="number" id="part" name="part" placeholder="Part..." size="3" value="{{ $book->part }}">
    <label for="publisher">Publisher:</label>
    <input list="publishers" value="{{ old('publisher_name', $book->publisher) }}" id="publisher" name="publisher_name" placeholder="Publisher...">
    <datalist id="publishers">
        @foreach($publishers as $publisher)
            <option value="{{ $publisher->name }}"></option>
        @endforeach
    </datalist>
    <input type="submit">
</form>
<x-validation_errors></x-validation_errors>
