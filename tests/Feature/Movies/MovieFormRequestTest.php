<?php
use App\Http\Requests\MovieFormRequest;
use Carbon\Carbon;
use MohammedManssour\FormRequestTester\TestsFormRequests;

uses(TestsFormRequests::class);

it('fails if the title is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['title' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title field is required.']);
});

it('fails if the title is not a string', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['title' => ['Some title']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title must be a string.']);
});

it('fails if the blurb is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['blurb' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb field is required.']);
});

it('fails if the blurb is not a string', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['blurb' => ['Some blurb']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be a string.']);
});

it('fails if the blurb contains less than three words', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['blurb' => 'The blurb'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be at least 3 words.']);
});

it('fails if the release_year is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['release_year' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year field is required.']);
});

it('fails if the release_year is not numeric', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['release_year' => 'Nineteen Ninety Three'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be a number.']);
});

it('fails if the release_year is less than four digits', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['release_year' => '193'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must have at least 4 digits.']);
});

it('fails if the release_year is more than four digits', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['release_year' => '19333'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must not have more than 4 digits.']);
});

it('fails is the release year is more than a year into the future', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['release_year' => Carbon::now()->addYear(2)->year])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be between 1800 and '. Carbon::now()->addYear(1)->year . '.']);
});

it('fails if the genre is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['genre_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name field is required.']);
});

it('fails if the genre is not a string', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['genre_name' => ['Some genre']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name must be a string.']);
});

it('fails if the format is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['format_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name field is required.']);
});

it('fails if the format is not a string', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['format_name' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name must be a string.']);
});

it('fails if the runtime is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['runtime' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The runtime field is required.']);
});

it('fails if the runtime is not numeric', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['runtime' => 'Ten'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The runtime must be a number.']);
});

it('fails if the runtime is less than two digits', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['runtime' => 1])
        ->assertValidationFailed()
        ->assertValidationMessages(['The runtime must have at least 2 digits.']);
});

it('fails if the runtime is more than three digits', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['runtime' => 4444])
        ->assertValidationFailed()
        ->assertValidationMessages(['The runtime must not have more than 3 digits.']);
});

it('fails if the actor is missing', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['actor' => []])
        ->assertValidationFailed()
        ->assertValidationMessages(['The actor field is required.']);
});

it('fails if the actor is not an array', function () {
    $this->formRequest(MovieFormRequest::class)
        ->post(['actor' => 'Clint Eastwood'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The actor must be an array.']);
});
