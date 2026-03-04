<?php

Route::get('/', function(){return redirect('/studies');});
Route::get('/studies',[AppHttpControllersWebStudyWebController::class,'index']);
Route::get('/studies/create', function(){ return 'create form not implemented'; });
Route::get('/studies/(d+)', [AppHttpControllersWebStudyWebController::class,'show']);
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SPK web routes
use App\Http\Controllers\Web\StudyWebController;
Route::get('/', function(){ return redirect('/studies'); });
Route::get('/studies', [StudyWebController::class,'index']);
Route::get('/studies/create', function(){ return 'create form not implemented'; });
Route::get('/studies/{id}', [StudyWebController::class,'show']);
