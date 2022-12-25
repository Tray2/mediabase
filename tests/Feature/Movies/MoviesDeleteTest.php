<?php

use App\Models\Movie;
use Database\Seeders\MediaTypeSeeder;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\delete;

uses(FastRefreshDatabase::class);

it('deletes a movie', function () {
    $this->seed(MediaTypeSeeder::class);
    $movie = Movie::factory()->create();
    assertDatabaseCount(Movie::class, 1);

    delete(route('movies.delete', $movie))
        ->assertRedirect(route('movies.index'));

    assertDatabaseCount(Movie::class, 0);

});
