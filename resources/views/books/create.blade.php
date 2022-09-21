This is the coming create books page.
<form action="{{ route('books.store') }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" placeholder="Title..."/>
    <label for="published_year">Published:</label>
    <input type="text" id="published_year" name="published_year" placeholder="YYYY" size="4">
    <label for="author">Author</label>
    <input list="authors" id="author" name="author" placeholder="Author...">
    <datalist id="authors"></datalist>
    <label for="format">Format</label>
    <input list="formats" id="format" name="format" placeholder="Format...">
    <datalist id="formats"></datalist>
    <label for="genre">Genre</label>
    <input list="genres" id="genre" name="genre" placeholder="Genre...">
    <datalist id="genres"></datalist>
</form>
