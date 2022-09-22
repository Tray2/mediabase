This is the coming create books page.
<form action="{{ route('books.store') }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" placeholder="Title..."/>
    <label for="published_year">Published:</label>
    <input type="text" id="published_year" name="published_year" placeholder="YYYY" size="4">
    <label for="author">Author:</label>
    <input list="authors" id="author" name="author" placeholder="Author...">
    <datalist id="authors">
        @foreach($authors as $author)
            <option value="{{ $author->last_name }}, {{ $author->first_name }}">
        @endforeach
    </datalist>
    <label for="format">Format:</label>
    <input list="formats" id="format" name="format" placeholder="Format...">
    <datalist id="formats">
        @foreach($formats as $format)
            <option value="{{ $format->name }}"></option>
        @endforeach
    </datalist>
    <label for="genre">Genre:</label>
    <input list="genres" id="genre" name="genre" placeholder="Genre...">
    <datalist id="genres">
        @foreach($genres as $genre)
            <option value="{{ $genre->name }}"></option>
        @endforeach
    </datalist>
    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn" placeholder="ISBN...">
    <label for="blurb">Blurb:</label>
    <textarea name="blurb" id="blurb" cols="30" rows="10"></textarea>
    <label for="series">Series:</label>
    <input list="series-list" id="series" name="series" placeholder="Series...">
    <datalist id="series-list">
        @foreach($series as $item)
            <option value="{{ $item->name }}"></option>
        @endforeach
    </datalist>
    <label for="part">part:</label>
    <input type="number" id="part" name="part" placeholder="Part..." size="3">
    <label for="publisher">Publisher:</label>
    <input list="publishers" id="publisher" name="publisher" placeholder="Publisher...">
    <datalist id="publishers">
        @foreach($publishers as $publisher)
            <option value="{{ $publisher->name }}"></option>
        @endforeach
    </datalist>
</form>
