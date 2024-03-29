<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
   return view('welcome');
});

routesForModel('books');
routesForModel('games');
routesForModel('movies');
routesForModel('records');
routesForModel('profiles');
authRoutes();

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
