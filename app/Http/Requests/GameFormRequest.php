<?php

namespace App\Http\Requests;

use App\Rules\MinWords;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class GameFormRequest extends FormRequest
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
            'genre_name' => ['required', 'string'],
            'format_name' => ['required', 'string'],
            'platform_name' => ['required', 'string'],
        ];
    }
}
