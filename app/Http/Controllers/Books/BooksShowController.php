<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\BookShowView;

class BooksShowController extends Controller
{
    public function __invoke(BookShowView $bookShowView)
    {
        return view('books.show')->with([
            'book' => $bookShowView,
        ]);
    }
}
