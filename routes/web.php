<?php

use App\Http\Controllers\BooksCreateController;
use App\Http\Controllers\BooksIndexController;
use App\Http\Controllers\BooksShowController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', BooksIndexController::class)->name('books.index');
Route::get('/books/create', BooksCreateController::class)->name('books.create');
Route::get('/books/{bookShowView}', BooksShowController::class)->name('books.show');
Route::post('/books/store', BooksCreateController::class)->name('books.store');
