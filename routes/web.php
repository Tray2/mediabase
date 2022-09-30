<?php

use App\Http\Controllers\BooksCreateController;
use App\Http\Controllers\BooksDeleteController;
use App\Http\Controllers\BooksEditController;
use App\Http\Controllers\BooksIndexController;
use App\Http\Controllers\BooksShowController;
use App\Http\Controllers\BooksStoreController;
use App\Http\Controllers\BooksUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', BooksIndexController::class)->name('books.index');
Route::get('/books/create', BooksCreateController::class)->name('books.create');
Route::get('/books/{bookShowView}', BooksShowController::class)->name('books.show');
Route::post('/books/store', BooksStoreController::class)->name('books.store');
Route::get('/books/edit/{bookShowView}', BooksEditController::class)->name('books.edit');
Route::put('books/{book}', BooksUpdateController::class)->name('books.update');
Route::delete('books/{book}', BooksDeleteController::class)->name('books.delete');
