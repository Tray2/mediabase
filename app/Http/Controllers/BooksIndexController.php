<?php

namespace App\Http\Controllers;

use App\Models\BookIndexView;

class BooksIndexController extends Controller
{
    public function __invoke()
    {
        return view('books.index')
            ->with([
                'books' => BookIndexView::query()
                    ->orderBy('author_name')
                    ->orderBy('series')
                    ->orderBy('part')
                    ->orderBy('published_year')
                    ->get()
            ]);
    }
}
