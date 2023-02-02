<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\MovieShowView;

class MoviesShowController extends Controller
{
    public function __invoke(MovieShowView $movieShowView)
    {
        return view('movies.show')
            ->with([
                'movie' => $movieShowView,
                'actors' => $movieShowView->actors,
            ]);
    }
}
