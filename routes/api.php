<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudyController;

Route::get('/studies', [StudyController::class, 'index']);
Route::post('/studies', [StudyController::class, 'store']);
Route::get('/studies/{id}', [StudyController::class, 'show']);
Route::post('/studies/{id}/run', [StudyController::class, 'run']);
