<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RecordFormRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
          'artist_id' => 'required|exists:artists,id',
          'title' => 'required',
          'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
          'genre_id' => 'required|exists:genres,id',
          'format_id' => 'required|exists:formats,id',
          'release_code' => 'required',
           'barcode' => 'sometimes'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:records,id'];
        }
        return $rules;
    }
}
