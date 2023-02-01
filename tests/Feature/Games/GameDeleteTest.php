<?php

use App\Models\Game;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;

it('deletes a game', function () {
    $game = Game::factory()->create();
    assertDatabaseCount(Game::class, 1);

    delete(route('games.delete', $game))
        ->assertRedirect(route('games.index'));

    assertDatabaseCount(Game::class, 0);
});
