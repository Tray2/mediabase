<?php

use App\Http\Requests\RecordFormRequest;
use Carbon\Carbon;
use MohammedManssour\FormRequestTester\TestsFormRequests;

uses(TestsFormRequests::class);

it('fails if the title is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['title' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title field is required.']);
});

it('fails if the title is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['title' => ['Some title']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The title must be a string.']);
});

it('fails if the barcode is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['barcode' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The barcode field is required.']);
});

it('fails if the barcode is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['barcode' => ['011550555']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The barcode must be a string.']);
});

it('fails if the spine code is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['spine_code' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The spine code field is required.']);
});

it('fails if the spine code is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['spine_code' => ['011550555']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The spine code must be a string.']);
});

it('fails if the release_year is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['release_year' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year field is required.']);
});

it('fails if the release_year is not numeric', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['release_year' => 'Nineteen Ninety Three'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be a number.']);
});

it('fails if the release_year is less than four digits', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['release_year' => '193'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must have at least 4 digits.']);
});

it('fails if the release_year is more than four digits', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['release_year' => '19333'])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must not have more than 4 digits.']);
});

it('fails is the release year is more than a year into the future', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['release_year' => Carbon::now()->addYear(2)->year])
        ->assertValidationFailed()
        ->assertValidationMessages(['The release year must be between 1800 and '.Carbon::now()->addYear(1)->year.'.']);
});

it('fails if the genre is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['genre_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name field is required.']);
});

it('fails if the genre is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['genre_name' => ['Some genre']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The genre name must be a string.']);
});

it('fails if the format is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['format_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name field is required.']);
});

it('fails if the format is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['format_name' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The format name must be a string.']);
});

it('fails if the artist is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['artist' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The artist field is required.']);
});

it('fails if the artist is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['artist' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The artist must be a string.']);
});

it('fails if the country name is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['country_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The country name field is required.']);
});

it('fails if the country name is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['country_name' => ['Some Country']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The country name must be a string.']);
});

it('fails if the record label name is missing', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['record_label_name' => ''])
        ->assertValidationFailed()
        ->assertValidationMessages(['The record label name field is required.']);
});

it('fails if the record label name is not a string', function () {
    $this->formRequest(RecordFormRequest::class)
        ->post(['record_label_name' => ['Some format']])
        ->assertValidationFailed()
        ->assertValidationMessages(['The record label name must be a string.']);
});
