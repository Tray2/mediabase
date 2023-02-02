<?php
use App\Http\Controllers\Games\GamesCreateController;
use App\Http\Controllers\Games\GamesDeleteController;
use App\Http\Controllers\Games\GamesEditController;
use App\Http\Controllers\Games\GamesIndexController;
use App\Http\Controllers\Games\GamesShowController;
use App\Http\Controllers\Games\GamesStoreController;
use App\Http\Controllers\Games\GamesUpdateController;

Route::get('/games', GamesIndexController::class)->name('games.index');
Route::get('/games/create', GamesCreateController::class)->name('games.create');
Route::get('/games/{gameShowView}', GamesShowController::class)->name('games.show');
Route::post('/games', GamesStoreController::class)->name('games.store');
Route::get('games/edit/{gameShowView}', GamesEditController::class)->name('games.edit');
Route::put('games/{game}', GamesUpdateController::class)->name('games.update');
Route::delete('/games/{game}', GamesDeleteController::class)->name('games.delete');
