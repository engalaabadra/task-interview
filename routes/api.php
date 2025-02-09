<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('tasks', TaskController::class);
Route::customResource('tasks', TaskController::class);
Route::customResourceFiles('tasks', TaskController::class);

