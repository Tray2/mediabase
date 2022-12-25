<?php

use App\Http\Controllers\Books\BooksCreateController;
use App\Http\Controllers\Books\BooksDeleteController;
use App\Http\Controllers\Books\BooksEditController;
use App\Http\Controllers\Books\BooksIndexController;
use App\Http\Controllers\Books\BooksShowController;
use App\Http\Controllers\Books\BooksStoreController;
use App\Http\Controllers\Books\BooksUpdateController;
use App\Http\Controllers\Movies\MoviesCreateController;
use App\Http\Controllers\Movies\MoviesDeleteController;
use App\Http\Controllers\Movies\MoviesEditController;
use App\Http\Controllers\Movies\MoviesIndexController;
use App\Http\Controllers\Movies\MoviesShowController;
use App\Http\Controllers\Movies\MoviesStoreController;
use App\Http\Controllers\Movies\MoviesUpdateController;
use App\Http\Controllers\Records\RecordsCreateController;
use App\Http\Controllers\Records\RecordsDeleteController;
use App\Http\Controllers\Records\RecordsEditController;
use App\Http\Controllers\Records\RecordsIndexController;
use App\Http\Controllers\Records\RecordsShowController;
use App\Http\Controllers\Records\RecordsStoreController;
use App\Http\Controllers\Records\RecordsUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', BooksIndexController::class)->name('books.index');
Route::get('/books/create', BooksCreateController::class)->name('books.create');
Route::get('/books/{bookShowView}', BooksShowController::class)->name('books.show');
Route::post('/books/store', BooksStoreController::class)->name('books.store');
Route::get('/books/edit/{bookShowView}', BooksEditController::class)->name('books.edit');
Route::put('/books/{book}', BooksUpdateController::class)->name('books.update');
Route::delete('/books/{book}', BooksDeleteController::class)->name('books.delete');

Route::get('/records', RecordsIndexController::class)->name('records.index');
Route::get('/records/create', RecordsCreateController::class)->name('records.create');
Route::get('/records/{recordShowView}', RecordsShowController::class)->name('records.show');
Route::post('/records', RecordsStoreController::class)->name('records.store');
Route::get('/records/edit/{recordShowView}', RecordsEditController::class)->name('records.edit');
Route::put('/records/{record}', RecordsUpdateController::class)->name('records.update');
Route::delete('/records/{record}', RecordsDeleteController::class)->name('records.delete');

Route::get('/movies', MoviesIndexController::class)->name('movies.index');
Route::get('/movies/create', MoviesCreateController::class)->name('movies.create');
Route::get('/movies/{movie}', MoviesShowController::class)->name('movies.show');
Route::post('/movies', MoviesStoreController::class)->name('movies.store');
Route::get('movies/edit/{movie}', MoviesEditController::class)->name('movies.edit');
Route::put('movies/{movie}', MoviesUpdateController::class)->name('movies.update');
Route::delete('/movies/{movie}', MoviesDeleteController::class)->name('movies.delete');
