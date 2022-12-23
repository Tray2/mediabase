<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use Database\Seeders\MediaTypeSeeder;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use function Pest\Laravel\get;

uses(FastRefreshDatabase::class);

beforeEach(function () {
    $this->seed(MediaTypeSeeder::class);
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'movie')
        ->value('id');

    $this->format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $this->genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
});

it('shows all information about a movie', function () {
    $movie = Movie::factory()->create([
        'title' => 'Congo',
        'release_year' => 1995,
        'runtime' => 104,
        'format_id' => $this->format->id,
        'genre_id' => $this->genre->id,
        'blurb' => 'An expedition on its way...'
    ]);
    get(route('movies.show', $movie))
        ->assertOk()
        ->assertSee([
            $movie->title,
            $movie->release_year,
            $movie->format->name,
            $movie->genre->name,
            $movie->runtime,
            $movie->blurb
        ]);
});
