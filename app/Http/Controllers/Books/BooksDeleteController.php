<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BooksDeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Book $book)
    {
        $book->authors()->detach();
        $book->delete();

        return redirect(route('books.index'));
    }
}
