<?php

use App\Models\Game;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

it('deletes a game', function () {
    $this->user = User::factory()->create();

    $game = Game::factory()->create();
    assertDatabaseCount(Game::class, 1);

    actingAs($this->user)->delete(route('games.delete', $game))
        ->assertRedirect(route('games.index'));

    assertDatabaseCount(Game::class, 0);
});
