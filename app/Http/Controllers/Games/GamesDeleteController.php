<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;

class GamesDeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Game $game)
    {
        $game->delete();

        return redirect(route('games.index'));
    }
}
