<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameFormatView;
use App\Models\GameGenreView;
use App\Models\Platform;

class GamesCreateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        return view('games.create')
            ->with([
                'genres' => GameGenreView::orderBy('name')->get(),
                'formats' => GameFormatView::orderBy('name')->get(),
                'platforms' => Platform::orderBy('name')->get(),
            ]);
    }
}
