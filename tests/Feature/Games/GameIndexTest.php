<?php

use App\Models\Format;
use App\Models\Game;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use App\Models\User;
use Database\Seeders\MediaTypeSeeder;
use Sinnbeck\DomAssertions\Asserts\AssertElement;
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
        'name' => 'PS5',
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
            'format_id' => $this->format->id,
        ]);
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id,
        ]);

    get(route('games.index'))
        ->assertOk()
        ->assertSeeInOrder([
            'Bazooka Bill',
            'Zelda',
        ]);
});

it('sorts the games with the same title by year', function () {
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'release_year' => 1988,
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id,
        ]);
    Game::factory()
        ->create([
            'title' => 'Bazooka Bill',
            'release_year' => 1986,
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id,
        ]);

    get(route('games.index'))
        ->assertOk()
        ->assertSeeInOrder([
            1986,
            1988,
        ]);
});

it('display a link to the games.create route when a user is signed in', function () {
    actingAs(User::factory()->create())
        ->get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->contains('a', ['href' => route('games.create')]);
        });
});

it('does not display a link to the games.create route for a guest', function () {
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->doesntContain('a', ['href' => route('games.create')]);
        });
});

it('has a link to the games.show route for each title', function () {
    $this->seed(MediaTypeSeeder::class);
    $game = Game::factory()->create();
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) use($game) {
            $element->contains('a', ['href' => route('games.show', $game->id)]);
        });
});

it('has a link to filter on release year', function () {
    $this->seed(MediaTypeSeeder::class);
    $game = Game::factory()->create();
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) use($game) {
            $element
                ->contains('a', ['href' => route('games.index', ['released' => $game->release_year])]);
        });
});

it('has a link to filter on platform', function () {
    $this->seed(MediaTypeSeeder::class);
    $game = Game::factory()->create();
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) use($game) {
            $element
                ->contains('a', ['href' => route('games.index', ['platform' => $game->platform->name])]);
        });
});

it('has a link to filter on genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $game = Game::factory()->create();
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) use($game) {
            $element
                ->contains('a', ['href' => route('games.index', ['genre' => $game->genre->name])]);
        });
});

it('has a link to filter on format', function () {
    $this->seed(MediaTypeSeeder::class);
    $game = Game::factory()->create();
    get(route('games.index'))
        ->assertElementExists(function(AssertElement $element) use($game) {
            $element
                ->contains('a', ['href' => route('games.index', ['format' => $game->format->name])]);
        });
});

it('has a link to reset any filters applied', function () {
    get(route('games.index'))
        ->assertOk()
        ->assertElementExists(function(AssertElement $element) {
            $element
                ->contains('main > a', [
                    'href' => route('games.index'),
                    'text' => 'Show All',
                ]);
        });
});

it('filters on the release year if the query string contains a year', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameToSee = Game::factory()->create(['release_year' => 2002]);
    $gameNotToSee = Game::factory()->create(['release_year' => 2001]);
    get(route('games.index', ['released' => 2002]))
        ->assertOk()
        ->assertSeeText([$gameToSee->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('filters on the platform if the query string contains a platform', function () {
    $this->seed(MediaTypeSeeder::class);
    $platformToSee = Platform::factory()->create();
    $platformNotToSee = Platform::factory()->create();
    $gameToSee1 = Game::factory()->create(['platform_id' => $platformToSee->id]);
    $gameNotToSee = Game::factory()->create(['platform_id' => $platformNotToSee->id]);

    get(route('games.index', ['platform' => $platformToSee->name]))
        ->assertOk()
        ->assertSeeText([$gameToSee1->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('filters on the genre if the query string contains a genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameMediaId = MediaType::where('name', 'game')->value('id');
    $genreToSee = Genre::factory()->create(['media_type_id' => $gameMediaId, 'name' => 'First Person Shooter']);
    $genreNotToSee = Genre::factory()->create(['media_type_id' => $gameMediaId, 'name' => 'Roll Playing Game']);
    $gameToSee1 = Game::factory()->create(['genre_id' => $genreToSee->id]);
    $gameNotToSee = Game::factory()->create(['genre_id' => $genreNotToSee->id]);

    get(route('games.index', ['genre' => $genreToSee->name]))
        ->assertOk()
        ->assertSeeText([$gameToSee1->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('filters on the format if the query string contains a format', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameMediaId = MediaType::where('name', 'book')->value('id');
    $formatToSee = Format::factory()->create(['media_type_id' => $gameMediaId]);
    $formatNotToSee = Format::factory()->create(['media_type_id' => $gameMediaId]);
    $gameToSee1 = Game::factory()->create(['format_id' => $formatToSee->id]);
    $gameNotToSee = Game::factory()->create(['format_id' => $formatNotToSee->id]);

    get(route('games.index', ['format' => $formatToSee->name]))
        ->assertOk()
        ->assertSeeText([$gameToSee1->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('filters on the title when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameToSee = Game::factory()->create();
    $gameNotToSee = Game::factory()->create();

    get(route('games.index', ['search' => $gameToSee->title]))
        ->assertOk()
        ->assertSeeText([$gameToSee->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('filters on partial titles', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameToSee = Game::factory()->create(['title' => 'The Dragon Reborn']);
    $gameNotToSee = Game::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('games.index', ['search' => 'Drag']))
        ->assertOk()
        ->assertSeeText([$gameToSee->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});

it('has case insensitive search', function () {
    $this->seed(MediaTypeSeeder::class);
    $gameToSee = Game::factory()->create(['title' => 'The Dragon Reborn']);
    $gameNotToSee = Game::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('games.index', ['search' => 'tHe DragOn rebOrn']))
        ->assertOk()
        ->assertSeeText([$gameToSee->title])
        ->assertDontSeeText([$gameNotToSee->title]);
});
