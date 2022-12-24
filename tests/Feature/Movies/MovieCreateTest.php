<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sinnbeck\DomAssertions\Asserts\AssertForm;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can show movies.create view', function () {
    get(route('movies.create'))
        ->assertOk();
});

it('has a form with the correct post action and method', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->hasMethod('post')
                ->hasAction(route('movies.store'))
                ->hasCSRF();
        });
});

it('has a title field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'title'
            ])
                ->containsInput([
                    'id' => 'title',
                    'name' => 'title'
                ]);
        });
});

it('has a release year field', function () {
    get(route('movies.create'))
        ->assertOk()
        ->assertFormExists(function (AssertForm $form) {
            $form->containsLabel([
                'for' => 'release_year'
            ])
                ->containsInput([
                    'id' => 'release_year',
                    'name' => 'release_year'
                ]) ;
        });
});
