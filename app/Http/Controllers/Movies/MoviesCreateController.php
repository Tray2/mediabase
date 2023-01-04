<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\MovieFormatView;
use App\Models\MovieGenreView;

class MoviesCreateController extends Controller
{
    public function __invoke()
    {
        return view('movies.create')
            ->with([
                'formats' => MovieFormatView::query()
                    ->orderBy('name')
                    ->get(),
                'genres' => MovieGenreView::query()
                    ->orderBy('name')
                    ->get(),
                'actors' => Actor::query()
                    ->orderBy('first_name')
                    ->orderBy('last_name')
                    ->get(),
            ]);
    }
}
