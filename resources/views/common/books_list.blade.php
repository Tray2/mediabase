<table class="mt-6 w-full">
    <tr class="text-left bg-gray-500 text-xl">
        <th class="py-2 pl-2">Author</th>
        <th>Title</th>
        <th>Rating</th>
        <th>Series</th>
        <th>Part</th>
        <th>Released</th>
        <th>Genre</th>
        <th>Format</th>
        @auth
            @if( request()->path() == 'books')
                <th>Status</th>
            @endif
        @endauth
    </tr>
    @if(count($books) == 0)
        <h3>No books found</h3>    
    @else
        @foreach($books as $book)
            <tr class="border-b-2 text-lg text-gray-800">
                <td class="pl-2 py-2">
                    @foreach($book->authors() as $author)
                        <a href="/authors/{{ $author->slug }}" class="hover:underline">{{ $author->name }}</a>
                        @if( $loop->index !== $loop->count -1) & @endif
                    @endforeach
                </td>
                <td><a href="/books/{{ $book->book_id }}" class="hover:underline">{{ $book->title }}</a></td>
                @if($book->rating != null)
                    <td>{{ $book->rating }}/5.0</td>
                @else 
                    <td>Not rated</td>
                @endif
                <td>{{ $book->series }}</td>
                <td>{{ $book->part }}</td>
                <td>{{ $book->released }}</td>
                <td><a href="/genres/{{ $book->genre_id }}" class="hover:underline">{{ $book->genre }}</a></td>
                <td><a href="/formats/{{ $book->format_id }}" class="hover:underline">{{ $book->format }}</a></td>
                @auth
                    @if(request()->path() == 'books')
                        <td>
                           @if($book->inCollection() > 0)
                                <span class="text-base bg-green-600 text-gray-100 py-1 px-2 rounded">Collected</span>
                           @else
                               <form method="POST" action="/bookcollections">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="submit" value="Add" title="Add book to your collection." 
                                           class="text-base bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                </form>
                            @endif
                        </td>
                        <td>
                            @if($book->isRead() > 0)
                                <span class="text-base bg-green-600 text-gray-100 py-1 px-2 rounded">Read</span>
                            @else
                                <form method="POST" action="/books/read">
                                     @csrf
                                     <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                                     <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                     <input type="submit" value="Mark Read" title="Mark as read." 
                                            class="text-base bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                 </form>
                            @endif
                     @endif
                @endauth
            </tr>
        @endforeach
    @endif
</table>
