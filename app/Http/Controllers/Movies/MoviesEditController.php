<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\MovieFormatView;
use App\Models\MovieGenreView;

class MoviesEditController extends Controller
{
    public function __invoke(Movie $movie)
    {
        return view('movies.edit')
            ->with([
                'movie' => $movie,
                'formats' => MovieFormatView::query()
                    ->orderBy('name')
                    ->get(),
                'genres' => MovieGenreView::query()
                    ->orderBy('name')
                    ->get(),
                'actors' => Actor::query()
                    ->orderBy('first_name')
                    ->orderBy('last_name')
                    ->get()
            ]);
    }
}
