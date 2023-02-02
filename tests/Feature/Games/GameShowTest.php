<?php

use App\Models\Format;
use App\Models\Game;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
});

it('shows all information about a game', function () {
    $game = Game::factory()->create([
        'format_id' => Format::factory()->create(['media_type_id' => $this->mediaTypeId]),
        'genre_id' => Genre::factory()->create(['media_type_id' => $this->mediaTypeId]),
        'platform_id' => Platform::factory()->create(['name' => 'PS5']),
    ]);

    get(route('games.show', $game))
        ->assertOk()
        ->assertSeeText([
            $game->title,
            $game->released_year,
            $game->format->name,
            $game->genre->name,
            $game->platform->name,
            $game->blurb,
        ]);
});
