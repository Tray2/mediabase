<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreFormRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'genre' => 'required|unique:genres,genre',
            'media_type_id' => 'required|exists:media_types,id'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:genres,id'];
        }

        return $rules;
    }
}
