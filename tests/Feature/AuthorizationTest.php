<?php

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('redirects to login if a guest tries to access the Create and Edit routes', function ($route) {
    get(route($route, 1))
        ->assertRedirect(route('login'));
})->with([
    'books.create',
    'books.edit',
    'games.create',
    'games.edit',
    'movies.create',
    'movies.edit',
    'records.create',
    'records.edit',
]);

it('redirects to login if a guest tries to access the Store routes', function ($route) {
    post(route($route, []))
        ->assertRedirect(route('login'));
})->with([
    'books.store',
    'games.store',
    'movies.store',
    'records.store',
]);

it('redirects to login if a guest tries to access the Update routes', function ($route) {
    put(route($route, 1), [])
        ->assertRedirect(route('login'));
})->with([
    'books.update',
    'games.update',
    'movies.update',
    'records.update',
]);

it('redirects to login if a guest tries to access the Delete routes', function ($route) {
    delete(route($route, 1))
        ->assertRedirect(route('login'));
})->with([
    'books.delete',
    'games.delete',
    'movies.delete',
    'records.delete',
]);
