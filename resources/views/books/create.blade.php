This is the coming create books page.
<form action="{{ route('books.store') }}" method="post">
    @csrf
    <label for="title">Title</label>
    <input type="text" name="title" placeholder="Title..."/>
    <input type="text" name="published_year" placeholder="YYYY" size="4">
</form>
