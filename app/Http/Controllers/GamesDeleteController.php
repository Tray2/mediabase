<?php

namespace App\Http\Controllers;

use App\Models\Game;

class GamesDeleteController extends Controller
{
    public function __invoke(Game $game)
    {
        $game->delete();

        return redirect(route('games.index'));
    }
}
