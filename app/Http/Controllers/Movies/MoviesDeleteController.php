<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class MoviesDeleteController extends Controller
{
    public function __invoke(Movie $movie)
    {
        $movie->delete();
        
        return redirect(route('movies.index'));
    }
}
