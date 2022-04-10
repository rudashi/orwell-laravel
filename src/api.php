<?php

use Illuminate\Support\Facades\Route;
use Rudashi\Orwell\WordController;

Route::get('api/orwell/{letters}', [WordController::class, 'allWords'])->name('api.orwell.search');
Route::post('api/orwell', [WordController::class, 'find'])->name('api.orwell.find');
