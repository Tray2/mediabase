<?php
use App\Http\Controllers\Records\RecordsCreateController;
use App\Http\Controllers\Records\RecordsDeleteController;
use App\Http\Controllers\Records\RecordsEditController;
use App\Http\Controllers\Records\RecordsIndexController;
use App\Http\Controllers\Records\RecordsShowController;
use App\Http\Controllers\Records\RecordsStoreController;
use App\Http\Controllers\Records\RecordsUpdateController;

Route::get('/records', RecordsIndexController::class)->name('records.index');
Route::get('/records/create', RecordsCreateController::class)->name('records.create');
Route::get('/records/{recordShowView}', RecordsShowController::class)->name('records.show');
Route::post('/records', RecordsStoreController::class)->name('records.store');
Route::get('/records/edit/{recordShowView}', RecordsEditController::class)->name('records.edit');
Route::put('/records/{record}', RecordsUpdateController::class)->name('records.update');
Route::delete('/records/{record}', RecordsDeleteController::class)->name('records.delete');
