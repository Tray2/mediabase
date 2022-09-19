<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can show books.create page', function () {
    get(route('books.create'))
        ->assertOk();
});

