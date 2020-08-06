<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookCollectionFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'book_id' => 'exists:books,id'
        ];
    }
}
