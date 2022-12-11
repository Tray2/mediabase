<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class MoviesIndexController extends Controller
{
    public function __invoke()
    {
        return view('movies.index')
            ->with([
                'movies' => Movie::query()
                    ->orderBy('title')
                    ->orderBy('release_year')
                    ->get()
            ]);
    }
}
