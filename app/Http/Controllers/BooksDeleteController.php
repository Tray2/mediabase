<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BooksDeleteController extends Controller
{
    public function __invoke(Book $book)
    {
        $book->authors()->detach();
        $book->delete();
        return redirect(route('books.index'));
    }
}
