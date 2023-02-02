<?php

use App\Models\Movie;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

it('deletes a movie', function () {
    $this->user = User::factory()->create();
    $movie = Movie::factory()->create();
    assertDatabaseCount(Movie::class, 1);

    actingAs($this->user)->delete(route('movies.delete', $movie))
        ->assertRedirect(route('movies.index'));

    assertDatabaseCount(Movie::class, 0);
});
