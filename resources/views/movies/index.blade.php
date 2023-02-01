@foreach($movies as $movie)
    {{ $movie->title }}
    {{ $movie->release_year }}
    {{ $movie->runtime }}
    {{ $movie->format }}
    {{ $movie->genre }}
@endforeach
