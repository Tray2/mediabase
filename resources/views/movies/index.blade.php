@foreach($movies as $movie)
    {{ $movie->title }}
    {{ $movie->release_year }}
    {{ $movie->length }}
    {{ $movie->format->name }}
    {{ $movie->genre->name }}
@endforeach
