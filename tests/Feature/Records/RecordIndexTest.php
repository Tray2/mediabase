<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Record;
use App\Models\Track;
use App\Models\User;
use Database\Seeders\MediaTypeSeeder;
use Sinnbeck\DomAssertions\Asserts\AssertElement;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'record')
        ->value('id');
});

it('lists records', function () {
    $fields = ['title', 'release_year'];
    $genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $artist = Artist::factory()->create();
    [$record1, $record2] = Record::factory()
                            ->count(2)
                            ->create([
                                'artist_id' => $artist->id,
                                'genre_id' => $genre->id,
                                'format_id' => $format->id,
                            ]);

    get(route('records.index'))
        ->assertOk()
        ->assertSeeText([
            ...$record1->only($fields),
            ...$record2->only($fields),
            $genre->name,
            $format->name,
            $artist->name,
        ]);
});

it('sorts records by artist', function () {
    Artist::factory()
        ->count(2)
        ->sequence(
            ['name' => 'Run Dmc'],
            ['name' => 'Public Enemy']
        )->has(Record::factory())
        ->create();

    get(route('records.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Public Enemy',
            'Run Dmc',
        ]);
});

it('sorts records by the same artist by released year', function () {
    Artist::factory()
        ->has(Record::factory()
        ->count(5)
        ->sequence(
            ['release_year' => 1986],
            ['release_year' => 1982],
            ['release_year' => 2006],
            ['release_year' => 1971],
            ['release_year' => 2004],
        ))
        ->create([
            'name' => 'Public Enemy',
        ]);

    get(route('records.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            1971,
            1982,
            1986,
            2004,
            2006,
        ]);
});

it('display a link to the records.create route when a user is signed in', function () {
    actingAs(User::factory()->create())
        ->get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->contains('a', ['href' => route('records.create')]);
        });
});

it('does not display a link to the records.create route for a guest', function () {
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->doesntContain('a', ['href' => route('records.create')]);
        });
});

it('has a link to the records.show route for each title', function () {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) use($record) {
            $element->contains('a', ['href' => route('records.show', $record->id)]);
        });
});

it('has a link to filter on artist', function () {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) use($record) {
            $element
                ->contains('a', ['href' => route('records.index', ['artist' => $record->artist->name])]);
        });
});

it('has a link to filter on release year', function () {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) use($record) {
            $element
                ->contains('a', ['href' => route('records.index', ['released' => $record->release_year])]);
        });
});

it('has a link to filter on genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) use($record) {
            $element
                ->contains('a', ['href' => route('records.index', ['genre' => $record->genre->name])]);
        });
});

it('has a link to filter on format', function () {
    $this->seed(MediaTypeSeeder::class);
    $record = Record::factory()->create();
    get(route('records.index'))
        ->assertElementExists(function(AssertElement $element) use($record) {
            $element
                ->contains('a', ['href' => route('records.index', ['format' => $record->format->name])]);
        });
});

it('has a link to reset any filters applied', function () {
    get(route('records.index'))
        ->assertOk()
        ->assertElementExists(function(AssertElement $element) {
            $element
                ->contains('main > a', [
                    'href' => route('records.index'),
                    'text' => 'Show All',
                ]);
        });
});

it('filters on the artist if the query string contains an artist', function () {
    $this->seed(MediaTypeSeeder::class);
    $artistToSee = Artist::factory()->create(['name' => 'Run DMC']);
    $artistNotToSee = Artist::factory()->create(['name' => 'Running Wild']);
    $recordToSee = Record::factory()->create(['artist_id' => $artistToSee->id, 'title' => 'King Of Rock']);
    $recordNotToSee = Record::factory()->create(['artist_id' => $artistNotToSee->id, 'title' => 'Black Hand Inn']);
    get(route('records.index', ['artist' => $artistToSee->id]))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on the release year if the query string contains a year', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['release_year' => 2002]);
    $recordNotToSee = Record::factory()->create(['release_year' => 2001]);
    get(route('records.index', ['released' => 2002]))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on the genre if the query string contains a genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordMediaId = MediaType::where('name', 'record')->value('id');
    $genreToSee = Genre::factory()->create(['media_type_id' => $recordMediaId, 'name' => 'Funk']);
    $genreNotToSee = Genre::factory()->create(['media_type_id' => $recordMediaId, 'name' => 'Jazz']);
    $recordToSee1 = Record::factory()->create(['genre_id' => $genreToSee->id]);
    $recordNotToSee = Record::factory()->create(['genre_id' => $genreNotToSee->id]);

    get(route('records.index', ['genre' => $genreToSee->name]))
        ->assertOk()
        ->assertSeeText([$recordToSee1->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on the format if the query string contains a format', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordMediaId = MediaType::where('name', 'book')->value('id');
    $formatToSee = Format::factory()->create(['media_type_id' => $recordMediaId, 'name' => 'LP']);
    $formatNotToSee = Format::factory()->create(['media_type_id' => $recordMediaId, 'name' => 'CD']);
    $recordToSee1 = Record::factory()->create(['format_id' => $formatToSee->id]);
    $recordNotToSee = Record::factory()->create(['format_id' => $formatNotToSee->id]);

    get(route('records.index', ['format' => $formatToSee->name]))
        ->assertOk()
        ->assertSeeText([$recordToSee1->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});


it('filters on the title when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['title' => 'Mama Said Knock You Out']);
    $recordNotToSee = Record::factory()->create(['title' => 'Yo! Bum Rush The Show']);

    get(route('records.index', ['search' => $recordToSee->title]))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on partial titles', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['title' => 'The Dragon Reborn']);
    $recordNotToSee = Record::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('records.index', ['search' => 'Drag']))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('has case insensitive search', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['title' => 'The Dragon Reborn']);
    $recordNotToSee = Record::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('records.index', ['search' => 'tHe DragOn rebOrn']))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on track titles if the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['title' => 'The Dragon Reborn']);
    $recordNotToSee = Record::factory()->create(['title' => 'Pawn Of Prophecy']);
    Track::factory()->create(['title' => 'Public Enemy No. 1', 'record_id' => $recordToSee->id ]);
    Track::factory()->create(['title' => 'Mama Said Knock You Out', 'record_id' => $recordNotToSee->id]);
    get(route('records.index', ['search' => 'Public Enemy No. 1']))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on track titles if the query string contains a search term when multiple tracks match', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSeeOne = Record::factory()->create(['title' => 'The Dragon Reborn']);
    $recordToSeeTwo = Record::factory()->create(['title' => 'The Sword Of Truth']);
    $recordNotToSee = Record::factory()->create(['title' => 'Pawn Of Prophecy']);
    Track::factory()->create(['title' => 'Public Enemy No. 1', 'record_id' => $recordToSeeOne->id ]);
    Track::factory()->create(['title' => 'Sleeping With The Enemy', 'record_id' => $recordToSeeTwo->id ]);
    Track::factory()->create(['title' => 'Mama Said Knock You Out', 'record_id' => $recordNotToSee->id]);

    get(route('records.index', ['search' => 'Enemy']))
        ->assertOk()
        ->assertSeeText([$recordToSeeOne->title])
        ->assertSeeText([$recordToSeeTwo->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});

it('filters on partial track titles', function () {
    $this->seed(MediaTypeSeeder::class);
    $recordToSee = Record::factory()->create(['title' => 'The Dragon Reborn']);
    $recordNotToSee = Record::factory()->create(['title' => 'Pawn Of Prophecy']);
    Track::factory()->create(['title' => 'Public Enemy No. 1', 'record_id' => $recordToSee->id ]);
    Track::factory()->create(['title' => 'Mama Said Knock You Out', 'record_id' => $recordNotToSee->id]);
    get(route('records.index', ['search' => 'Enemy']))
        ->assertOk()
        ->assertSeeText([$recordToSee->title])
        ->assertDontSeeText([$recordNotToSee->title]);
});
