<?php

namespace App\Utils;

use Carbon\Carbon;

class TimeRecognition
{
    public static function run($content = '', $timeZone = 'UTC') {
        $recognizedTimes = [];

        $patterns = [];
        $patterns[] = "/\d{4}\-\d{2}\-\d{2}\ \d{2}\:\d{2}/"; // 2010-01-01 12:15
        $patterns[] = "/\d{4}\.\d{2}\.\d{2}(\.|)\ \d{2}\:\d{2}/";
        
        $patterns[] = "/(?<!\d{2}\ )(?<!\d{2}\.\ )\d{2}\:\d{2}/"; // 14:40 without two number and space before it
        $patterns[] = "/(?<!\d{2}.)\d{2}\.\d{2}(\.|)\ \d{2}\:\d{2}/"; //06.16 16:40

        $matches = [];
        foreach ($patterns as $pattern) {
            $m = [];
            preg_match_all($pattern, $content, $m);
            if (isset($m[0]) && count($m[0])) {
                array_push($matches, ...$m[0]);
            }
        }

        foreach ($matches as $time) {
            $time = str_replace('. ', ' ', $time);
            $time = str_replace('.', '-', $time);

            switch (strlen($time)) {
                case 5:
                    $time = Carbon::now()->timezone($timeZone)->format('Y-m-d') . ' ' . $time . ':00';
                    break;
                case 11:
                    $time = Carbon::now()->timezone($timeZone)->format('Y') . '-' . $time . ':00';
                    break;
                case 16:
                    $time .= ':00';
                    break;
            }

            try {
                Carbon::parse($time);
                $recognizedTimes[] = $time;
            } catch (\Exception $e) {

            }
        }

        return $recognizedTimes;
    }
}