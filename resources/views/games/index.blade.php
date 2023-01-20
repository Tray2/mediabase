@foreach( $games as $game)
    {{ $game->title }}
    {{ $game->release_year }}
    {{ $game->platform }}
    {{ $game->genre }}
    {{ $game->format }}
@endforeach
