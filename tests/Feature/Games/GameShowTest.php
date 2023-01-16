<?php

use App\Models\Format;
use App\Models\Game;
use App\Models\Genre;
use App\Models\MediaType;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
});

it('shows all information about a game', function () {
    $game = Game::factory()->create([
        'format_id' => Format::factory()->create(['media_type_id' => $this->mediaTypeId]),
        'genre_id' => Genre::factory()->create(['media_type_id' => $this->mediaTypeId]),
    ]);
    get(route('games.show', $game))
        ->assertOk()
        ->assertSeeText([
            $game->title,
            $game->released_year,
            $game->format->name,
            $game->genre->name,
            $game->platform,
            $game->blurb,
        ]);
});
