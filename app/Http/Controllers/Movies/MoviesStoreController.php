<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieFormRequest;
use App\Models\Movie;

class MoviesStoreController extends Controller
{
    public function __invoke(MovieFormRequest $request)
    {
        $valid = $request->validated();

        $movie = Movie::create(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_id' => $request->getFormatId(),
        ]));
        $movie->actors()->attach($request->getActor());

        return redirect(route('movies.index'));
    }
}
