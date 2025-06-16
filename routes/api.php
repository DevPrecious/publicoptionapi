<?php

use App\Http\Controllers\Poll\PollController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/poll', [PollController::class, 'createPoll']);
Route::get('/poll/{unique_code}', [PollController::class, 'viewPoll']);
