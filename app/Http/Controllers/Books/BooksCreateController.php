<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\BookFormatView;
use App\Models\BookGenreView;
use App\Models\Publisher;
use App\Models\Series;

class BooksCreateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        return view('books.create')
            ->with([
                'authors' => Author::query()
                            ->select('id', 'last_name', 'first_name')
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
                            ->select('id', 'name')
                            ->orderBy('name')
                            ->get(),
                'publishers' => Publisher::query()
                            ->select('id', 'name')
                            ->orderBy('name')
                            ->get(),
            ]);
    }
}
