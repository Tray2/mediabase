<?php

namespace App\Http\Requests;

use App\Rules\Isbn;
use App\Rules\MinWords;
use App\Rules\RequiredIfNotStandalone;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'published_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,'.Carbon::now()->addYear(1)->year],
            'isbn' => ['required', new Isbn()],
            'blurb' => ['required', new MinWords(3)],
            'author' => 'required',
            'genre_name' => 'required',
            'format_name' => 'required',
            'series_name' => 'required',
            'publisher_name' => 'required',
            'part' => [new RequiredIfNotStandalone($this->series_name)],

        ];
    }
}
