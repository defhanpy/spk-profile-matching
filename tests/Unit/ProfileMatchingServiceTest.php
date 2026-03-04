<?php

use PHPUnit\Framework\TestCase;
use App\Services\ProfileMatchingService;

class ProfileMatchingServiceTest extends TestCase
{
    public function test_basic_calculation()
    {
        $service = new ProfileMatchingService();
        $criteria = [
            ['id'=>1,'weight'=>1,'type'=>'CF','ideal'=>5],
            ['id'=>2,'weight'=>1,'type'=>'SF','ideal'=>3],
        ];
        $alts = [
            ['id'=>1,'name'=>'A','values'=>[1=>5,2=>3]],
            ['id'=>2,'name'=>'B','values'=>[1=>4,2=>2]],
        ];

        $res = $service->calculate($alts,$criteria);
        $this->assertCount(2,$res);
        $this->assertEquals('A',$res[0]['name']);
        $this->assertGreaterThan($res[1]['score'],$res[0]['score']);
    }
}
