{{ $movie->title }}
{{ $movie->release_year }}
{{ $movie->runtime }}
{{ $movie->blurb }}
{{ $movie->format }}
{{ $movie->genre }}

@foreach($movie->actors as $actor)
    {{ $actor->full_name }}
@endforeach
