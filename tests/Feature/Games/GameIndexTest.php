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

    $this->format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $this->genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);

    $this->platform = Platform::factory()->create([
        'name' => 'PS5'
    ]);
});

it('lists games', function () {
    $game = Game::factory()
        ->create([
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
            'platform_id' => $this->platform->id,
        ]);

    get('/games')
        ->assertOk()
        ->assertSeeText([
            $game->title,
            $game->release_year,
            $game->format->name,
            $game->genre->name,
            $game->platform->name,
        ]);
});

it('sorts the games by title', function () {
    Game::factory()
        ->create([
            'title' => 'Zelda',
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id
        ]);
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id
        ]);

    get(route('games.index'))
        ->assertOk()
        ->assertSeeInOrder([
            'Bazooka Bill',
            'Zelda'
        ]);
});

it('sorts the games with the same title by year', function () {
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'release_year' => 1988,
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id
        ]);
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'release_year' => 1986,
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id
        ]);

    get(route('games.index'))
        ->assertOk()
        ->assertSeeInOrder([
            1986,
            1988
        ]);
});
