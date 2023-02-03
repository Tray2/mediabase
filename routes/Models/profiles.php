<?php
use App\Http\Controllers\Profiles\ProfileDeleteController;
use App\Http\Controllers\Profiles\ProfileEditController;
use App\Http\Controllers\Profiles\ProfileUpdateController;

Route::get('/profile', ProfileEditController::class)->name('profile.edit');
Route::patch('/profile', ProfileUpdateController::class)->name('profile.update');
Route::delete('/profile', ProfileDeleteController::class)->name('profile.destroy');
