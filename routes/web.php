<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

// start game
Route::get('/', [MainController::class, 'startGame'])->name('startGame');
Route::post('/', [MainController::class,  'prepareGame'])->name('prepareGame');
