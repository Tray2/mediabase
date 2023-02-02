    <?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Platform;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use Sinnbeck\DomAssertions\Asserts\AssertForm;

beforeEach(function () {
    $mediaTypeId = MediaType::query()
        ->where('name', 'game')
        ->value('id');
    $this->genre = Genre::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->format = Format::factory()->create(['media_type_id' => $mediaTypeId]);
    $this->platform = Platform::factory()->create();
    $this->validGame = [
        'title' => 'Some Title',
        'release_year' => 1984,
        'blurb' => 'Some boring text',
        'genre_name' => $this->genre->name,
        'format_name' => $this->format->name,
        'platform_name' => $this->platform->name,
    ];
    $this->user = User::factory()->create();

    actingAs($this->user)->get(route('games.create'));
});

it('stores a valid game', function () {
    actingAs($this->user)->post(route('games.store', $this->validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
});

it('creates a new genre if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['genre_name'] = 'Fantasy';

    actingAs($this->user)->post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('genres', ['name' => 'Fantasy']);
});

it('creates a new format if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['format_name'] = 'Hardcover';

    actingAs($this->user)->post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('formats', ['name' => 'Hardcover']);
});

it('creates a new platform if the one passed does not exist in the database', function () {
    $validGame = $this->validGame;
    $validGame['platform_name'] = 'Fantasy';

    actingAs($this->user)->post(route('games.store', $validGame))
        ->assertRedirect(route('games.index'));
    assertDatabaseCount('games', 1);
    assertDatabaseHas('platforms', ['name' => 'Fantasy']);
});

it('has the old values in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['title'] = '';

    actingAs($this->user)->post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('title');
    actingAs($this->user)->get(route('games.create'))
        ->assertOk()
        ->assertSeeText('The title field is required.')
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'release_year',
                'value' => $this->validGame['release_year'],
            ])
                ->contains('textarea', [
                    'name' => 'blurb',
                    'value' => $this->validGame['blurb'],
                ])
                ->containsInput([
                    'name' => 'format_name',
                    'value' => $this->validGame['format_name'],
                ])
                ->containsInput([
                    'name' => 'genre_name',
                    'value' => $this->validGame['genre_name'],
                ])
                ->containsInput([
                    'name' => 'platform_name',
                    'value' => $this->validGame['platform_name'],
                ]);
        });
});

it('has the old title value in the form if the validation fails', function () {
    $invalidGame = $this->validGame;
    $invalidGame['blurb'] = '';

    actingAs($this->user)->post(route('games.store', $invalidGame))
        ->assertRedirect(route('games.create'))
        ->assertSessionHasErrorsIn('title');
    actingAs($this->user)->get(route('games.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsInput([
                'name' => 'title',
                'value' => $this->validGame['title'],
            ]);
        });
});
