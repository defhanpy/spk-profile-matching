<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StudyWebController;

Route::get('/', function(){ return redirect('/studies'); });
Route::get('/studies', [StudyWebController::class,'index']);
Route::get('/studies/create', function(){ return 'create form not implemented'; });
Route::get('/studies/{id}', [StudyWebController::class,'show']);
