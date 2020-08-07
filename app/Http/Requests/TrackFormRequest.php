<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackFormRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
          'track_no' => 'required',
          'title' => 'required',
          'mix' => 'required',
          'record_id' => 'required|exists:records,id'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules += ['id' => 'required|exists:tracks,id'];
        }

        return $rules;

    }
}
