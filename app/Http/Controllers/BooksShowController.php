<?php

namespace App\Http\Controllers;

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
