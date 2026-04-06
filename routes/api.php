<?php

use App\Http\Controllers\ProfilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('profiles', ProfilesController::class)->only(['index', 'show']);
Route::resource('profiles', ProfilesController::class)->only(['store', 'update', 'destroy'])->middleware('auth:sanctum');

require __DIR__ . '/auth.php';