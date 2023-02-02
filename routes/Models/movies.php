<?php
use App\Http\Controllers\Movies\MoviesCreateController;
use App\Http\Controllers\Movies\MoviesDeleteController;
use App\Http\Controllers\Movies\MoviesEditController;
use App\Http\Controllers\Movies\MoviesIndexController;
use App\Http\Controllers\Movies\MoviesShowController;
use App\Http\Controllers\Movies\MoviesStoreController;
use App\Http\Controllers\Movies\MoviesUpdateController;

Route::get('/movies', MoviesIndexController::class)->name('movies.index');
Route::get('/movies/create', MoviesCreateController::class)->name('movies.create');
Route::get('/movies/{movieShowView}', MoviesShowController::class)->name('movies.show');
Route::post('/movies', MoviesStoreController::class)->name('movies.store');
Route::get('/movies/edit/{movieShowView}', MoviesEditController::class)->name('movies.edit');
Route::put('/movies/{movie}', MoviesUpdateController::class)->name('movies.update');
Route::delete('/movies/{movie}', MoviesDeleteController::class)->name('movies.delete');
