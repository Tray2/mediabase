<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\BookShowView;
use App\Models\Format;
use App\Models\Genre;
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
            'formats' => Format::query()
                    ->orderBy('name')
                    ->get(),
            'genres' => Genre::query()
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
