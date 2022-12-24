<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;

class MoviesCreateController extends Controller
{
    public function __invoke()
    {
        return view('movies.create');
    }
}
