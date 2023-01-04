<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use function Pest\Laravel\get;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

uses(FastRefreshDatabase::class);

beforeEach(function () {
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

it('lists movies', function () {
    $movie = Movie::factory()
        ->create([
            'title' => 'Die Hard',
            'release_year' => 1990,
            'runtime' => '1h 58m',
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);

    get('/movies')
        ->assertOk()
        ->assertSee([
            $movie->title,
            $movie->release_year,
            $movie->runtime,
            $movie->genre->name,
            $movie->format->name,
        ]);
});

it('sorts movies by title', function () {
    $movie1 = Movie::factory()
        ->create([
            'title' => 'Where Eagles Dare',
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);
    $movie2 = Movie::factory()
        ->create([
            'title' => 'Jurassic Park',
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);

    get(route('movies.index'))
        ->assertOk()
        ->assertSeeInOrder([
            $movie2->title,
            $movie1->title,
        ]);
});

it('sorts movies with the same title by release year', function () {
    $movie1 = Movie::factory()
        ->create([
            'title' => 'Generic Title',
            'release_year' => 1989,
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);
    $movie2 = Movie::factory()
        ->create([
            'title' => 'Generic Title',
            'release_year' => 1976,
            'format_id' => $this->format->id,
            'genre_id' => $this->genre->id,
        ]);

    get(route('movies.index'))
        ->assertOk()
        ->assertSeeInOrder([
            $movie2->release_year,
            $movie1->release_year,
        ]);
});
