<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreFormRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'genre' => 'required|unique:genres,genre',
            'type' => 'required'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:genres,id'];
        }

        return $rules;
    }
}
