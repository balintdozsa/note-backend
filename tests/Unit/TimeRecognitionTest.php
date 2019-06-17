<?php


namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Utils\TimeRecognition;

class TimeRecognitionTest extends TestCase
{
    public function testTimeRecognition() {
        $content = '
            Test Note
            
            abc 12:14
            FAIL: 12:66
            
            2019.10.10 12:44
            2019.10.10. 12:45
            abc 2019-10-10 12:46
            FAIL: 2019.10.32. 12:47
            
            10.10 12:54
            10.10. 12:55
        ';

        $timeZone = 'Europe/Budapest';

        $tr = TimeRecognition::run($content, $timeZone);
        sort($tr);
        //var_dump($tr);

        $this->assertCount(6, $tr);

        $this->assertEquals(Carbon::now()->timezone($timeZone)->format('Y-m-d').' 12:14:00', $tr[0]);
        $this->assertEquals('2019-10-10 12:44:00', $tr[1]);
        $this->assertEquals('2019-10-10 12:45:00', $tr[2]);
        $this->assertEquals('2019-10-10 12:46:00', $tr[3]);
        $this->assertEquals('2019-10-10 12:54:00', $tr[4]);
        $this->assertEquals('2019-10-10 12:55:00', $tr[5]);
    }
}