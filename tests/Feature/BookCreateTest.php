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
            'name="title"',
        ], false);
});

it('has a published_year field', function () {
    get(route('books.create'))
        ->assertSee([
            'name="published_year"',
        ], false);
});
