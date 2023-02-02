<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieFormRequest;
use App\Models\Movie;
use App\Services\ForeignKeyService;

class MoviesStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(MovieFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        $movie = Movie::create(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'movie'),
            'format_id' => $foreignKeyService->getFormatId($request->format_name, 'movie'),
        ]));
        $movie->actors()->attach($foreignKeyService->getActorIds($request->actor));

        return redirect(route('movies.index'));
    }
}
