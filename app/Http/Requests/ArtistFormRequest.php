<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtistFormRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:artists,name',
            'slug' => 'nullable'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules +=  ['id' => 'required|exists:artists,id'];
        }

        return $rules;
    }
}
