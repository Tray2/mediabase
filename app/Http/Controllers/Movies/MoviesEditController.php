<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\MovieFormatView;
use App\Models\MovieGenreView;
use App\Models\MovieShowView;

class MoviesEditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(MovieShowView $movieShowView)
    {
        return view('movies.edit')
            ->with([
                'movie' => $movieShowView,
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
