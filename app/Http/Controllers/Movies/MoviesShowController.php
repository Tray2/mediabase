<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class MoviesShowController extends Controller
{
    public function __invoke(Movie $movie)
    {
        return view('movies.show')
            ->with([
                'movie' => $movie,
            ]);
    }
}
