<table>
    <tr>
        <th>Author:</th>
        <th>Title:</th>
        <th>Published:</th>
        <th>Series:</th>
        <th>Part:</th>
    </tr>
    @foreach($books as $book)
        <tr>
            <td>{{ $book->author_name }}</td>
            <td>{{ $book->title }}</td>
            <td>{{ $book->published_year }}</td>
            <td>{{ $book->series }}</td>
            <td>{{ $book->part }}</td>
        </tr>
    @endforeach
</table>
