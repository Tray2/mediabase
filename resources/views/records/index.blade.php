<table>
    <tr>
        <th>Artist</th>
        <th>Title</th>
        <th>Released</th>
        <th>Genre</th>
        <th>Format</th>
    </tr>

    @foreach($records as $record)
        <tr>
            <td>{{ $record->artist }}</td>
            <td>{{ $record->title }}</td>
            <td>{{ $record->release_year }}</td>
            <td>{{ $record->genre_name }}</td>
            <td>{{ $record->format_name }}</td>
        </tr>
    @endforeach
</table>
