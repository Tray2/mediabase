<table class="mt-6 w-full">
    <tr class="text-left bg-gray-500 text-xl">
        <th class="py-2 pl-2">Artist</th>
        <th>Title</th>
        <th>Rating</th>
        <th>Released</th>
        <th>Genre</th>
        <th>Format</th>
        @auth
            @if( request()->path() == 'records')
                <th>Status</th>
            @endif
        @endauth
    </tr>
    @if(count($records) == 0)
        <h3>No records found</h3>
    @else
        @foreach($records as $record)
            <tr class="border-b-2 text-lg text-gray-800">
                <td class="pl-2 py-2">
                    <a href="/artists/{{ $record->slug }}" class="hover:underline">{{ $record->name }}</a>
                </td>
                <td><a href="/records/{{ $record->record_id }}" class="hover:underline">{{ $record->title }}</a></td>
                @if($record->rating != null)
                    <td>{{ $record->rating }}/5.0</td>
                @else
                    <td>Not rated</td>
                @endif
                <td>{{ $record->released }}</td>
                <td><a href="/genres/{{ $record->genre_id }}" class="hover:underline">{{ $record->genre }}</a></td>
                <td><a href="/formats/{{ $record->format_id }}" class="hover:underline">{{ $record->format }}</a></td>
                @auth
                    @if(request()->path() == 'records')
                        <td>
                            @if($record->inCollection() > 0)
                                <span class="text-base bg-green-600 text-gray-100 py-1 px-2 rounded">Collected</span>
                            @else
                                <form method="POST" action="/recordcollections">
                                    @csrf
                                    <input type="hidden" name="record_id" value="{{ $record->record_id }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="submit" value="Add" title="Add record to your collection."
                                           class="text-base bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                </form>
                            @endif
                        </td>
                        <td>
                    @endif
                @endauth
            </tr>
        @endforeach
    @endif
</table>
