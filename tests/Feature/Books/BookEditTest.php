<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\Series;
use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(MediaTypeSeeder::class);
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
    $this->book = Book::factory()->create();
});

it('can show books.edit page', function () {
    get(route('books.edit', $this->book))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'method="post"',
            'action="'.route('books.update', $this->book).'"',
        ], false);
});

it('has a method field with the action put', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'name="_method"',
            'value="PUT"',
        ], false);
});

it('has a token field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'name="_token"',
        ], false);
});

it('has a title field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="title"',
            'id="title"',
            'name="title"',
        ], false);
});

it('has a published_year field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="published_year',
            'id="published_year"',
            'name="published_year"',
        ], false);
});

it('has an author field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="author',
            'id="author"',
            'name="author[]"',
            'list="authors',
            'datalist id="authors',
        ], false);
});

it('has a format field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="format',
            'id="format"',
            'name="format_name"',
            'list="formats',
            'datalist id="formats',
        ], false);
});

it('has a genres field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="genre',
            'id="genre"',
            'name="genre_name"',
            'list="genres',
            'datalist id="genres',
        ], false);
});

it('has an isbn field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="isbn',
            'id="isbn"',
            'name="isbn"',
        ], false);
});

it('has a blurb text area', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="blurb',
            'id="blurb"',
            'name="blurb"',
        ], false);
});

it('has a series field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="series',
            'id="series"',
            'name="series_name"',
            'list="series-list',
            'datalist id="series-list',
        ], false);
});

it('has a part field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="part',
            'id="part"',
            'name="part"',
        ], false);
});

it('has a publishers field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'for="publisher',
            'id="publisher"',
            'name="publisher_name"',
            'list="publishers',
            'datalist id="publishers',
        ], false);
});

it('loads a list of authors that is sorted in alphabetical order', function () {
    Author::factory()
        ->count(2)
        ->sequence(
            [
                'last_name' => 'Goodkind',
                'first_name' => 'Terry',
            ],
            [
                'last_name' => 'Eddings',
                'first_name' => 'David',
            ]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Eddings, David',
            'Goodkind, Terry',
        ]);
});

it('loads a list of formats that is sorted in alphabetical order', function () {
    Format::factory()
        ->count(2)
        ->sequence(
            [
                'name' => 'Pocket',
                'media_type_id' => $this->mediaTypeId,
            ],
            [
                'name' => 'Hardcover',
                'media_type_id' => $this->mediaTypeId,
            ]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Hardcover',
            'Pocket',
        ]);
});

it('loads a list of genres that is sorted in alphabetical order', function () {
    Genre::factory()
        ->count(2)
        ->sequence(
            [
                'name' => 'Fantasy',
                'media_type_id' => $this->mediaTypeId,
            ],
            [
                'name' => 'Crime',
                'media_type_id' => $this->mediaTypeId,
            ]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Crime',
            'Fantasy',
        ]);
});

it('loads a list of series that is sorted in alphabetical order', function () {
    Series::factory()
        ->count(2)
        ->sequence(
            ['name' => 'The Wheel Of Time'],
            ['name' => 'The Sword Of Truth']
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'The Sword Of Truth',
            'The Wheel Of Time',
        ]);
});

it('loads a list of publishers that is sorted in alphabetical order', function () {
    Publisher::factory()
        ->count(2)
        ->sequence(
            ['name' => 'TOR'],
            ['name' => 'Ace Books']
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Ace Books',
            'TOR',
        ]);
});

it('has the title of the book in the title field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->title.'"',
        ], false);
});

it('has the published year of the book in the published year field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->published_year.'"',
        ], false);
});

it('has the isbn of the book in the isbn field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->isbn.'"',
        ], false);
});

it('has the blurb of the book in the blurb field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            $this->book->blurb,
        ], false);
});

it('has the part of the book in the part field', function () {
    get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->part.'"',
        ], false);
});

it('has the format of the book in the format field', function () {
    $pattern = '/<input(.)*value="'.$this->book->format->name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->format->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the genre of the book in the genre field', function () {
    $pattern = '/<input(.)*value="'.$this->book->genre->name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->genre->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the series of the book in the series field', function () {
    $pattern = '/<input(.)*value="'.$this->book->series->name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->series->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the publisher of the book in the publisher field', function () {
    $pattern = '/<input(.)*value="'.$this->book->publisher->name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->publisher->name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the author of the book in the author field', function () {
    $this->book->authors()->attach(Author::factory()->create());
    $pattern = '/<input(.)*value="'.$this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern, $response->content());
});

it('has the authors of a book in two author fields', function () {
    $this->book->authors()->attach(Author::factory()->create());
    $this->book->authors()->attach(Author::factory()->create());

    $pattern1 = '/<input(.)*value="'.$this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name.'"(.)*>/';
    $pattern2 = '/<input(.)*value="'.$this->book->authors[1]->last_name.', '.$this->book->authors[1]->first_name.'"(.)*>/';
    $response = get(route('books.edit', $this->book))
        ->assertSee([
            'value="'.$this->book->authors[0]->last_name.', '.$this->book->authors[0]->first_name.'"',
            'value="'.$this->book->authors[1]->last_name.', '.$this->book->authors[1]->first_name.'"',
        ], false);
    $this->assertMatchesRegularExpression($pattern1, $response->content());
    $this->assertMatchesRegularExpression($pattern2, $response->content());
});

it('has an add author button', function () {
    get(route('books.edit', $this->book))
        ->assertSee('title="Add Author"', false);
});

it('has a delete author button', function () {
    get(route('books.edit', $this->book))
        ->assertSee('title="Remove Author"', false);
});

it('has a update button', function () {
    get(route('books.edit', $this->book))
        ->assertSee('<input type="submit">', false);
});
