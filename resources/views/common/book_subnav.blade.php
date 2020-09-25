@section('subnav')
<div class="px-6 md:px-0 bg-blue-900 shadow mb-8 pb-4">
    <nav class="container mx-auto pl-48 flex justify-left">
        <a class="no-underline hover:underline text-gray-300 text-lg
            @php
              if(strpos(request()->path(), 'authors') !== false)
                echo 'italic'
            @endphp
            " href="{{ route('authors.index') }}">Authors</a>
        <a class="no-underline hover:underline text-gray-300 text-lg ml-4
            @php
              if(strpos(request()->path(), 'formats') !== false )
                echo 'italic'
            @endphp
            " href="{{ route('formats.index', 'type=BOOKS') }}">Formats</a>
        <a class="no-underline hover:underline text-gray-300 text-lg ml-4
            @php
              if(strpos(request()->path(), 'genres') !== false )
                echo 'italic'
            @endphp
            " href="{{ route('genres.index'), 'type=BOOKS' }}">Genres</a>
    </nav>
</div>
@endsection
