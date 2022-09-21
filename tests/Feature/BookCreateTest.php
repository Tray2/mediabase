<?php

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
            'name="author"',
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


