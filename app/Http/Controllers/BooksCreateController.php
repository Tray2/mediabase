<?php

namespace App\Http\Controllers;

class BooksCreateController extends Controller
{
    public function __invoke()
    {
        return view('books.create');
    }
}
