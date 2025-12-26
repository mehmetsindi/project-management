<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/gantt/{project}', [\App\Http\Controllers\GanttController::class, 'getData'])
    ->middleware('auth:sanctum');
