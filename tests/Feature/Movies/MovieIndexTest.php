<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Movie;
use App\Models\User;
use Database\Seeders\MediaTypeSeeder;
use Sinnbeck\DomAssertions\Asserts\AssertElement;
use function Pest\Laravel\get;

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
            'runtime' => 118,
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

it('display a link to the movies.create route when a user is signed in', function () {
    actingAs(User::factory()->create())
        ->get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->contains('a', ['href' => route('movies.create')]);
        });
});

it('does not display a link to the movies.create route for a guest', function () {
    get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->doesntContain('a', ['href' => route('movies.create')]);
        });
});

it('has a link to the movies.show route for each title', function () {
    $this->seed(MediaTypeSeeder::class);
    $movie = Movie::factory()->create();
    get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) use($movie) {
            $element->contains('a', ['href' => route('movies.show', $movie->id)]);
        });
});

it('has a link to filter on release year', function () {
    $this->seed(MediaTypeSeeder::class);
    $movie = Movie::factory()->create();
    get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) use($movie) {
            $element
                ->contains('a', ['href' => route('movies.index', ['released' => $movie->release_year])]);
        });
});

it('has a link to filter on genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $movie = Movie::factory()->create();
    get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) use($movie) {
            $element
                ->contains('a', ['href' => route('movies.index', ['genre' => $movie->genre->name])]);
        });
});

it('has a link to filter on format', function () {
    $this->seed(MediaTypeSeeder::class);
    $movie = Movie::factory()->create();
    get(route('movies.index'))
        ->assertElementExists(function(AssertElement $element) use($movie) {
            $element
                ->contains('a', ['href' => route('movies.index', ['format' => $movie->format->name])]);
        });
});

it('has a link to reset any filters applied', function () {
    get(route('movies.index'))
        ->assertOk()
        ->assertElementExists(function(AssertElement $element) {
            $element
                ->contains('main > a', [
                    'href' => route('movies.index'),
                    'text' => 'Show All',
                ]);
        });
});

it('filters on the release year if the query string contains a year', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieToSee = Movie::factory()->create(['release_year' => 2002]);
    $movieNotToSee = Movie::factory()->create(['release_year' => 2001]);
    get(route('movies.index', ['released' => 2002]))
        ->assertOk()
        ->assertSeeText([$movieToSee->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});

it('filters on the genre if the query string contains a genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieMediaId = MediaType::where('name', 'movie')->value('id');
    $genreToSee = Genre::factory()->create(['media_type_id' => $movieMediaId]);
    $genreNotToSee = Genre::factory()->create(['media_type_id' => $movieMediaId]);
    $movieToSee1 = Movie::factory()->create(['genre_id' => $genreToSee->id]);
    $movieNotToSee = Movie::factory()->create(['genre_id' => $genreNotToSee->id]);

    get(route('movies.index', ['genre' => $genreToSee->name]))
        ->assertOk()
        ->assertSeeText([$movieToSee1->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});

it('filters on the format if the query string contains a format', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieMediaId = MediaType::where('name', 'book')->value('id');
    $formatToSee = Format::factory()->create(['media_type_id' => $movieMediaId]);
    $formatNotToSee = Format::factory()->create(['media_type_id' => $movieMediaId]);
    $movieToSee1 = Movie::factory()->create(['format_id' => $formatToSee->id]);
    $movieNotToSee = Movie::factory()->create(['format_id' => $formatNotToSee->id]);

    get(route('movies.index', ['format' => $formatToSee->name]))
        ->assertOk()
        ->assertSeeText([$movieToSee1->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});


it('filters on the title when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieToSee = Movie::factory()->create();
    $movieNotToSee = Movie::factory()->create();

    get(route('movies.index', ['search' => $movieToSee->title]))
        ->assertOk()
        ->assertSeeText([$movieToSee->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});

it('filters on partial titles', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieToSee = Movie::factory()->create(['title' => 'The Dragon Reborn']);
    $movieNotToSee = Movie::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('movies.index', ['search' => 'Drag']))
        ->assertOk()
        ->assertSeeText([$movieToSee->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});

it('has case insensitive search', function () {
    $this->seed(MediaTypeSeeder::class);
    $movieToSee = Movie::factory()->create(['title' => 'The Dragon Reborn']);
    $movieNotToSee = Movie::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('movies.index', ['search' => 'tHe DragOn rebOrn']))
        ->assertOk()
        ->assertSeeText([$movieToSee->title])
        ->assertDontSeeText([$movieNotToSee->title]);
});
