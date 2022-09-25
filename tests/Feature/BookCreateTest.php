<?php

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can show books.create page', function () {
    get(route('books.create'))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('books.create'))
        ->assertSee([
            'method="post"',
            'action="' . route('books.store') . '"',
        ], false);
});

it('has a token field', function () {
    get(route('books.create'))
        ->assertSee([
            'name="_token"',
        ], false);
});

it('has a title field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="title"',
            'id="title"',
            'name="title"',
        ], false);
});


it('has a published_year field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="published_year',
            'id="published_year"',
            'name="published_year"',
        ], false);
});

it('has an author field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="author',
            'id="author"',
            'name="author[]"',
            'list="authors',
            'datalist id="authors',
        ], false);
});

it('has a format field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="format',
            'id="format"',
            'name="format"',
            'list="formats',
            'datalist id="formats',
        ], false);
});

it('has a genres field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="genre',
            'id="genre"',
            'name="genre"',
            'list="genres',
            'datalist id="genres',
        ], false);
});

it('has an isbn field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="isbn',
            'id="isbn"',
            'name="isbn"',
        ], false);
});

it('has a blurb text area', function () {
    get(route('books.create'))
        ->assertSee([
            'for="blurb',
            'id="blurb"',
            'name="blurb"',
        ], false);
});

it('has a series field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="series',
            'id="series"',
            'name="series"',
            'list="series-list',
            'datalist id="series-list',
        ], false);
});

it('has a part field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="part',
            'id="part"',
            'name="part"',
        ], false);
});

it('has a publishers field', function () {
    get(route('books.create'))
        ->assertSee([
            'for="publisher',
            'id="publisher"',
            'name="publisher"',
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
                'first_name' => 'Terry'
            ],
            [
                'last_name' => 'Eddings',
                'first_name' => 'David'
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
            ['name' => 'Pocket',],
            ['name' => 'Hardcover',]
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
            ['name' => 'Fantasy',],
            ['name' => 'Crime',]
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
            ['name' => 'The Wheel Of Time',],
            ['name' => 'The Sword Of Truth',]
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
            ['name' => 'TOR',],
            ['name' => 'Ace Books',]
        )
        ->create();

    get(route('books.create'))
        ->assertOk()
        ->assertSeeInOrder([
            'Ace Books',
            'TOR',
        ]);
});
