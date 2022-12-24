<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can show movies.create view', function () {
    get(route('movies.create'))
        ->assertOk();
});
