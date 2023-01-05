@foreach( $games as $game)
    {{ $game->title }}
    {{ $game->released_year }}
    {{ $game->platform }}
    {{ $game->genre }}
    {{ $game->format }}
@endforeach
