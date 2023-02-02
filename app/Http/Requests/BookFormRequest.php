<?php

namespace App\Http\Requests;

use App\Rules\Isbn;
use App\Rules\MinWords;
use App\Rules\NumericIfNotStandalone;
use App\Rules\RequiredIfNotStandalone;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'published_year' => [
                'required',
                'numeric',
                'min_digits:4',
                'max_digits:4',
                'between:1800,'.Carbon::now()->addYear(1)->year,
            ],
            'isbn' => ['required', new Isbn()],
            'blurb' => ['required', 'string', new MinWords(3)],
            'author' => ['required', 'array'],
            'genre_name' => ['required', 'string'],
            'format_name' => ['required', 'string'],
            'series_name' => ['required', 'string'],
            'publisher_name' => ['required', 'string'],
            'part' => [
                new RequiredIfNotStandalone($this->series_name),
                new NumericIfNotStandalone($this->series_name),
            ],
        ];
    }
}
