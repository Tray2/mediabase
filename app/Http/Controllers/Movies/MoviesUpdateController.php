<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieFormRequest;
use App\Models\Movie;

class MoviesUpdateController extends Controller
{
    public function __invoke(Movie $movie, MovieFormRequest $request)
    {
        $valid = $request->validated();

        $movie->update(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_it' => $request->getFormatId(),
        ]));

        $movie->actors()->sync($request->getActor());

        return redirect(route('movies.index'));
    }
}
