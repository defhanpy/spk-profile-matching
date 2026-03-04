<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfileMatchingService;
use App\Models\Study;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\AlternativeValue;

class RunSPK extends Command
{
    protected $signature = 'spk:run {--study=1}';
    protected $description = 'Run profile matching for a study and log results';

    public function handle(): int
    {
        $studyId = $this->option('study') ?: 1;
        $study = Study::find($studyId);
        if (!$study) {
            $this->error('Study not found');
            return 1;
        }

        $criteria = Criteria::where('study_id',$study->id)->get()->map(function($c){
            return ['id'=>$c->id,'weight'=>$c->weight,'type'=>$c->type,'ideal'=>floatval($c->ideal ?? 5.0)];
        })->toArray();

        $alts = Alternative::where('study_id',$study->id)->get()->map(function($a){
            $values = AlternativeValue::where('alternative_id',$a->id)->get()->pluck('value','criteria_id')->toArray();
            return ['id'=>$a->id,'name'=>$a->name,'values'=>$values];
        })->toArray();

        $service = new ProfileMatchingService();
        $results = $service->calculate($alts,$criteria);

        $logdir = config('app.runtime_path', base_path('storage')) . '/spk_logs';
        if (!is_dir($logdir)) mkdir($logdir, 0755, true);
        $outfile = $logdir.'/'.'spk_'.date('Ymd_His').'.json';
        file_put_contents($outfile, json_encode($results, JSON_PRETTY_PRINT));

        // also append to heartbeat log so notifications include a summary
        $hb = getenv('HOME').'/.config/spk_heartbeat/heartbeat.log';
        $summary = date('c').' - spk run: '.implode(', ', array_map(fn($r)=>$r['name']."(".$r['score'].")", $results));
        file_put_contents($hb, $summary."\n", FILE_APPEND);

        $this->info('SPK run complete, results saved: '.$outfile);
        return 0;
    }
}
