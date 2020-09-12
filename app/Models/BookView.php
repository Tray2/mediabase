<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BookView extends Model
{
    public function inCollection()
    {
        return BookCollection::where('book_id', $this->book_id)->where('user_id', Auth::user()->id)->count();
    }

    public function authors()
    {
        return Author::whereIn('id', explode(',', $this->author_id))->get();
    }

    public function isRead()
    {
        return BookRead::where('book_id', $this->book_id)->where('user_id', Auth::user()->id)->count();
    }
}
