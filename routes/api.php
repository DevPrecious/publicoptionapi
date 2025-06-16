<?php

use App\Http\Controllers\Poll\PollController;
use App\Http\Controllers\Vote\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/poll', [PollController::class, 'createPoll']);
Route::get('/poll/{unique_code}', [PollController::class, 'viewPoll']);
Route::post('/poll/{unique_code}/vote', [VoteController::class, 'vote']);
