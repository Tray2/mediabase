<?php

use App\Http\Requests\GameFormRequest;
use Carbon\Carbon;
use MohammedManssour\FormRequestTester\TestsFormRequests;

uses(TestsFormRequests::class);

it('fails if the title is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['title' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title field is required.']);
});

it('fails if the title is not a string', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['title' => ['Some title']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title must be a string.']);
});

it('fails if the blurb is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['blurb' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb field is required.']);
});

it('fails if the blurb is not a string', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['blurb' => ['Some blurb']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be a string.']);
});

it('fails if the blurb contains less than three words', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['blurb' => 'The blurb'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be at least 3 words.']);
});

it('fails if the release_year is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['release_year' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year field is required.']);
});

it('fails if the release_year is not numeric', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['release_year' => 'Nineteen Ninety Three'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be a number.']);
});

it('fails if the release_year is less than four digits', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['release_year' => '193'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must have at least 4 digits.']);
});

it('fails if the release_year is more than four digits', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['release_year' => '19333'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must not have more than 4 digits.']);
});

it('fails is the release year is more than a year into the future', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['release_year' => Carbon::now()->addYear(2)->year])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.']);
});

it('fails if the genre is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['genre_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name field is required.']);
});

it('fails if the genre is not a string', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['genre_name' => ['Some genre']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name must be a string.']);
});

it('fails if the format is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['format_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name field is required.']);
});

it('fails if the format is not a string', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['format_name' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name must be a string.']);
});

it('fails if the platform is missing', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['platform_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The platform name field is required.']);
});

it('fails if the platform is not a string', function () {
    $this->formRequest(GameFormRequest::class)
        ->post(['platform_name' => ['Some platform']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The platform name must be a string.']);
});
