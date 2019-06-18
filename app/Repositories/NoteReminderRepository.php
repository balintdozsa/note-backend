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

    public function getActiveReminders(&$ids = [], &$notifications = []) {
        $now = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:00');

        $noteReminders = $this->model
            ->join('notes', 'notes.id', '=', 'note_reminders.note_id')
            ->join('user_push_tokens', 'user_push_tokens.user_id', '=', 'notes.user_id')
            ->where('note_reminders.utc_notification_time', '<=', $now)
            ->where('note_reminders.sent', '=', 0)
            ->where('user_push_tokens.id', '!=', null)
            ->get([
                'notes.note',
                'note_reminders.id',
                'note_reminders.utc_notification_time',
                'user_push_tokens.push_token',
            ]);

        $ids = [];
        $notifications = [];

        foreach ($noteReminders as $noteReminder) {
            $ids[] = $noteReminder->id;

            $notifications[] = [
                'to' => 'ExponentPushToken['.$noteReminder->push_token.']',
                'title' => 'Reminder notification',
                'body' => $noteReminder->note,
                'sound' => 'default',
            ];
        }
    }
}