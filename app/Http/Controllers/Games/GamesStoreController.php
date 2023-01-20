<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameFormRequest;
use App\Models\Game;
use App\Services\ForeignKeyService;

class GamesStoreController extends Controller
{
    public function __invoke(GameFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        Game::create(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'game'),
            'format_id' => $foreignKeyService->getFormatId($request->format_name, 'game'),
            'platform_id' => $foreignKeyService->getPlatformId($request->platform_name),
        ]));
        return redirect(route('games.index'));
    }
}
