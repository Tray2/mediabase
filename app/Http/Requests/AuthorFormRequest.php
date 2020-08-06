<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorFormRequest extends FormRequest
{
    public function messages() {
      return [
          'first_name.unique' => 'Author name not unique',
          'slug' => null
      ];
    }

    public function rules()
    {
        $rules = [
            'first_name' => 'required|unique:authors,first_name,' . null . ',id,last_name,'. $this->last_name,
            'last_name' => 'required',
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:authors,id'];
        }

        return $rules;
    }
}
