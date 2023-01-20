<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieFormRequest;
use App\Models\Movie;
use App\Services\ForeignKeyService;

class MoviesUpdateController extends Controller
{
    public function __invoke(Movie $movie, MovieFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        $movie->update(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'movie'),
            'format_it' => $foreignKeyService->getFormatId($request->format_name, 'movie'),
        ]));

        $movie->actors()->sync($foreignKeyService->getActorIds($request->actor));

        return redirect(route('movies.index'));
    }
}
