<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\BookFormatView;
use App\Models\BookGenreView;
use App\Models\BookShowView;
use App\Models\Publisher;
use App\Models\Series;

class BooksEditController extends Controller
{
    public function __invoke(BookShowView $bookShowView)
    {
        return view('books.edit')->with([
            'book' => $bookShowView,
            'authors' => Author::query()
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->get(),
            'formats' => BookFormatView::query()
                    ->orderBy('name')
                    ->get(),
            'genres' => BookGenreView::query()
                    ->orderBy('name')
                    ->get(),
            'series' => Series::query()
                    ->orderBy('name')
                    ->get(),
            'publishers' => Publisher::query()
                    ->orderBy('name')
                    ->get(),
        ]);
    }
}
