<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Study;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Services\ProfileMatchingService;

class StudyController extends Controller
{
    public function index()
    {
        return response()->json(Study::withCount('alternatives')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'criteria' => 'required|array',
            'criteria.*.name' => 'required|string',
            'criteria.*.type' => 'required|in:CF,SF',
            'criteria.*.weight' => 'required|numeric',
            'criteria.*.ideal' => 'nullable|numeric',
            'alternatives' => 'required|array',
            'alternatives.*.name' => 'required|string',
            'alternatives.*.values' => 'required|array',
        ]);

        $study = Study::create(['title'=>$data['title'],'description'=>$data['description'] ?? null]);

        $critMap = [];
        foreach ($data['criteria'] as $c) {
            $crit = Criteria::create(array_merge($c,['study_id'=>$study->id]));
            $critMap[] = $crit->id;
        }

        foreach ($data['alternatives'] as $alt) {
            $a = Alternative::create(['study_id'=>$study->id,'name'=>$alt['name']]);
            foreach ($alt['values'] as $i=>$val) {
                // support either criteria index or id as key
                $criteria_id = is_numeric($i) ? $critMap[intval($i)] : intval($i);
                AlternativeValue::create(['alternative_id'=>$a->id,'criteria_id'=>$criteria_id,'value'=>$val]);
            }
        }

        return response()->json(['ok'=>true,'study_id'=>$study->id],201);
    }

    public function show($id)
    {
        $study = Study::with(['criteria','alternatives'=>function($q){$q->with('values');}])->findOrFail($id);
        return response()->json($study);
    }

    public function run($id)
    {
        $study = Study::with('criteria','alternatives')->findOrFail($id);
        $criteria = $study->criteria->map(function($c){
            return ['id'=>$c->id,'weight'=>floatval($c->weight),'type'=>$c->type,'ideal'=>floatval($c->ideal ?? 5.0)];
        })->toArray();

        $alts = [];
        foreach ($study->alternatives as $a) {
            $values = $a->values->pluck('value','criteria_id')->toArray();
            $alts[] = ['id'=>$a->id,'name'=>$a->name,'values'=>$values];
        }

        $service = new ProfileMatchingService();
        $results = $service->calculate($alts,$criteria);

        // save results
        foreach ($results as $r) {
            \DB::table('results')->updateOrInsert(
                ['study_id'=>$study->id,'alternative_id'=>$r['id']],
                ['score'=>$r['score'],'details'=>json_encode($r['details']),'updated_at'=>now(),'created_at'=>now()]
            );
        }

        // save json file
        $out = storage_path('spk_logs');
        if (!is_dir($out)) mkdir($out,0755,true);
        $file = $out.'/spk_'.date('Ymd_His').'.json';
        file_put_contents($file,json_encode($results,JSON_PRETTY_PRINT));

        return response()->json(['ok'=>true,'results'=>$results,'file'=>$file]);
    }
}
