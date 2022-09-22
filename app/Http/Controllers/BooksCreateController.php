<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;

class BooksCreateController extends Controller
{
    public function __invoke()
    {
        return view('books.create')
            ->with([
                'authors' => Author::query()
                            ->select('id', 'last_name', 'first_name')
                            ->orderBy('last_name')
                            ->orderBy('first_name')
                            ->get(),
                'formats' => Format::query()
                            ->select('id', 'name')
                            ->orderBy('name')
                            ->get(),
                'genres' => Genre::query()
                            ->select('id', 'name')
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
