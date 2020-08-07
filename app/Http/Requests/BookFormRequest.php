<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookFormRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'title' => 'required',
            'series' => 'required',
            'part' => [
                'required_unless:series,Standalone',
                'numeric',
                'nullable'
            ],
            'format_id' => 'required|exists:formats,id',
            'genre_id' => 'required|exists:genres,id',
            'isbn' => [
                'required',
                'regex:/^(97(8|9))?\d{9}(\d|X)$/'
            ],
            'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'reprinted' => ['nullable', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'pages' => 'required|integer',
            'blurb' => 'required'

        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:books,id'];
        }
        return $rules;
    }
}
