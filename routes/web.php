<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StudyWebController;

Route::get('/', function(){ return redirect('/studies'); });
Route::get('/studies', [StudyWebController::class,'index']);
Route::get('/studies/create', function(){ return 'create form not implemented'; });
Route::get('/studies/{id}', [StudyWebController::class,'show']);

// Web endpoint to run SPK (fallback when API routes not loaded)
use Illuminate\Http\Request;
Route::post('/studies/{id}/run', function(Request $req,$id){
    // run artisan command
    exec('php '.base_path('artisan').' spk:run --study='.escapeshellarg($id).' 2>&1',$out,$rc);
    $json = [];
    if($rc===0){
        $latest = collect(glob(storage_path('spk_logs/spk_*.json')))->sort()->last();
        $content = $latest ? file_get_contents($latest) : null;
        $json = ['ok'=>true,'results'=> $content ? json_decode($content,true): [], 'file'=>$latest];
    } else {
        $json = ['ok'=>false,'error'=>implode("\n",$out)];
    }
    return response()->json($json);
});
