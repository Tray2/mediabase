@foreach($movies as $movie)
    {{ $movie->title }}
    {{ $movie->release_year }}
    {{ $movie->runtime }}
    {{ $movie->format->name }}
    {{ $movie->genre->name }}
@endforeach
