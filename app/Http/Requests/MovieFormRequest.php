<?php

namespace App\Http\Requests;

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Rules\MinWords;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MovieFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'release_year' => [
                'required',
                'numeric',
                'min_digits:4',
                'max_digits:4',
                'between:1800,'.Carbon::now()->addYear(1)->year
            ],
            'blurb' => ['required', 'string', new MinWords(3)],
            'runtime' => ['required', 'numeric', 'min_digits:2', 'max_digits:3', 'between:1,999'],
            'actor' => ['required', 'array'],
            'genre_name' => ['required', 'string'],
            'format_name' => ['required', 'string'],
        ];
    }
}
