<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameFormRequest;
use App\Models\Game;
use App\Services\ForeignKeyService;

class GamesUpdateController extends Controller
{
    public function __invoke(Game $game, GameFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        $game->update(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'game'),
            'format_id' => $foreignKeyService->getFormatId($request->format_name,'game'),
            'platform_id' => $foreignKeyService->getPlatformId($request->platform_name),
        ]));

        return redirect(route('games.index'));
    }
}
