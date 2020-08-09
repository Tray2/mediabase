<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatFormRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'format' => 'required|unique:formats,format',
            'media_type_id' => 'required|exists:media_types,id'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:formats,id'];
        }

        return $rules;
    }
}
