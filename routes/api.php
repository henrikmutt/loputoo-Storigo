<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);
