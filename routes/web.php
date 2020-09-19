<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::resource('books', 'BooksController');

Route::resource('genres', 'GenresController');

Route::resource('formats', 'FormatsController');

Route::resource('authors', 'AuthorsController');

Route::resource('artists', 'ArtistsController');

Route::resource('records', 'RecordsController');

Route::resource('tracks', 'TracksController')->except(['index', 'show']);

Route::get('home', 'UserPagesController@index')->name('home');

Route::get('about', 'StaticPagesController@about')->name('about');
Route::get('contact', 'StaticPagesController@contact')->name('contact');
Route::get('/', 'StaticPagesController@start')->name('start');

Route::get('/bookcollections/{user}', 'BookCollectionsController@index')->name('bookcollections.index');
Route::post('/bookcollections', 'BookCollectionsController@store')->name('bookcollections.store');
Route::delete('/bookcollections/{book}', 'BookCollectionsController@destroy')->name('bookcollections.delete');
Route::get('/books/read/{user}', 'BookReadsController@index')->name('bookreads.index');
Route::post('/books/read', 'BookReadsController@store')->name('bookreads.store');
Route::delete('/books/read/{book}', 'BookReadsController@destroy')->name('bookreads.delete');

Route::get('/recordcollections/{user}', 'RecordCollectionsController@index')->name('recordcollections.index');

Auth::routes();
