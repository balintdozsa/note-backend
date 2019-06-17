<?php

namespace App\Repositories;

use App\Models\NoteReminder;
use Carbon\Carbon;


class NoteReminderRepository extends BaseRepository
{
    protected function getModel() {
        return NoteReminder::class;
    }

    public function addReminders($noteId, $recognizedTimes = [], $timeZone = 'UTC') {
        foreach ($recognizedTimes as $recognizedTime) {
            $localTime = Carbon::createFromFormat('Y-m-d H:i:s', $recognizedTime, $timeZone);
            $utcTime = Carbon::createFromFormat('Y-m-d H:i:s', $recognizedTime, $timeZone)
                ->setTimezone('UTC');

            $this->create([
                'note_id' => $noteId,
                'utc_notification_time' => $utcTime,
                'local_notification_time' => $localTime,
                'local_time_zone' => $timeZone,
            ]);
        }
    }
}