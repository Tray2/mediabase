<form action="{{route('movies.store')}}" method="post">
    @csrf
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <label for="release_year">Release Year</label>
    <input type="text" name="release_year" id="release_year">

</form>
