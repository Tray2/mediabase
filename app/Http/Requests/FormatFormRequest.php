<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatFormRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'format' => 'required|unique:formats,format',
            'type' => 'required'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:formats,id'];
        }

        return $rules;
    }
}
