<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Study;

class StudyWebController extends Controller
{
    public function index(){
        $studies = Study::withCount('alternatives')->get();
        return view('studies.index', compact('studies'));
    }
    public function show($id){
        $study = Study::with(['criteria','alternatives.values.criteria'])->findOrFail($id);
        return view('studies.show', compact('study'));
    }
}
