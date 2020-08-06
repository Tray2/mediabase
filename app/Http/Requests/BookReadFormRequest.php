<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookReadFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id'
        ];
    }
}
