<?php

use App\Http\Requests\BookFormRequest;
use Carbon\Carbon;
use MohammedManssour\FormRequestTester\TestsFormRequests;

uses(TestsFormRequests::class);

it('fails if the title is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['title' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title field is required.']);
});

it('fails if the title is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['title' => ['Some title']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title must be a string.']);
});

it('fails if the blurb is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['blurb' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb field is required.']);
});

it('fails if the blurb is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['blurb' => ['Some blurb']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be a string.']);
});

it('fails if the blurb contains less than three words', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['blurb' => 'The blurb'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The blurb must be at least 3 words.']);
});

it('fails if the published_year is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['published_year' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The published year field is required.']);
});

it('fails if the published_year is not numeric', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['published_year' => 'Nineteen Ninety Three'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The published year must be a number.']);
});

it('fails if the published_year is less than four digits', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['published_year' => '193'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The published year must have at least 4 digits.']);
});

it('fails if the published_year is more than four digits', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['published_year' => '19333'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The published year must not have more than 4 digits.']);
});

it('fails is the published year is more than a year into the future', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['published_year' => Carbon::now()->addYear(2)->year])
        ->assertValidationFailed()
        ->assertValidationMessages(['The published year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.']);
});

it('fails if the isbn is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['isbn' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The isbn field is required.']);
});

it('fails if the isbn is not a valid isbn10 or isbn13', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['isbn' => '97813985100000'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The isbn must be a valid ISBN10 or ISBN13.']);
});

it('fails if the isbn is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['isbn' => ['9781492041214']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The isbn must be a string.']);
});

it('fails if the part is missing and the book belongs to a series', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['part' => null, 'series_name' => 'The Wheel Of Time'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The part is required when book belongs to a series.']);
});

it('fails if the part is not numeric', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['part' => 'ten', 'series_name' => 'The Wheel Of Time'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The part must be a number.']);
});

it('fails if the author is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['author' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The author field is required.']);
});

it('fails if the author is not an array', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['author' => 'Jordan, Robert'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The author must be an array.']);
});

it('fails if the genre is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['genre_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name field is required.']);
});

it('fails if the genre is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['genre_name' => ['Some genre']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name must be a string.']);
});

it('fails if the format is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['format_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name field is required.']);
});

it('fails if the format is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['format_name' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name must be a string.']);
});

it('fails if the serie is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['series_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The series name field is required.']);
});

it('fails if the series is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['series_name' => ['Some Series'], 'part' => 1])
        ->assertValidationFailed()
        ->assertValidationMessages(['The series name must be a string.']);
});

it('fails if the publisher is missing', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['publisher_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The publisher name field is required.']);
});

it('fails if the publisher is not a string', function () {
    $this->formRequest(BookFormRequest::class)
        ->post(['publisher_name' => ['Some publisher']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The publisher name must be a string.']);
});
