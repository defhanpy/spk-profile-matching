<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Study;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\AlternativeValue;

class SPKSeeder extends Seeder
{
    public function run(): void
    {
        $study = Study::firstOrCreate(['title' => 'Seleksi Beasiswa 2026'], ['description' => 'Contoh studi kasus']);

        $criteria = [
            ['name'=>'IPK','type'=>'CF','weight'=>1,'ideal'=>4.0],
            ['name'=>'Organisasi','type'=>'SF','weight'=>1,'ideal'=>5.0],
            ['name'=>'Wawancara','type'=>'CF','weight'=>1,'ideal'=>4.0],
        ];

        $critIds = [];
        foreach ($criteria as $c) {
            $crt = Criteria::firstOrCreate(['study_id'=>$study->id,'name'=>$c['name']], $c);
            $critIds[] = $crt->id;
        }

        $alternatives = [
            ['name'=>'Alya','values'=>[4.0,5.0,3.5]],
            ['name'=>'Budi','values'=>[3.5,4.0,4.0]],
            ['name'=>'Citra','values'=>[4.0,3.0,4.5]],
        ];

        foreach ($alternatives as $alt) {
            $a = Alternative::firstOrCreate(['study_id'=>$study->id,'name'=>$alt['name']]);
            foreach ($alt['values'] as $i=>$val) {
                AlternativeValue::updateOrCreate(['alternative_id'=>$a->id,'criteria_id'=>$critIds[$i]], ['value'=>$val]);
            }
        }
    }
}
