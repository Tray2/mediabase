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

    $this->format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $this->genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
});

it('lists games', function () {
    $game = Game::factory()
        ->create([
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);

    get('/games')
        ->assertOk()
        ->assertSeeText([
            $game->title,
            $game->released_year,
            $game->format->name,
            $game->genre->name,
            $game->platform,
        ]);
})->skip();
