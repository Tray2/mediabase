<?php

use App\Models\Movie;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

it('deletes a movie', function () {
    $movie = Movie::factory()->create();
    assertDatabaseCount(Movie::class, 1);

    delete(route('movies.delete', $movie))
        ->assertRedirect(route('movies.index'));

    assertDatabaseCount(Movie::class, 0);
});
