<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now()->timezone('UTC')->format('Y-m-d H:i:00');
        //$now = Carbon::createFromFormat('Y-m-d H:i:s', '2019-07-09 22:00:00', 'UTC');

        $noteReminders = DB::table('note_reminders')
            ->join('notes', 'notes.id', '=', 'note_reminders.note_id')
            ->join('user_push_tokens', 'user_push_tokens.user_id', '=', 'notes.user_id')
            ->where('note_reminders.utc_notification_time', '=', $now)
            ->where('user_push_tokens.id', '!=', null)
            ->get(['notes.note','note_reminders.utc_notification_time','user_push_tokens.push_token']);

        $notifications = [];
        foreach ($noteReminders as $noteReminder) {
            $notifications[] = [
                'to' => 'ExponentPushToken['.$noteReminder->push_token.']',
                'title' => 'Reminder notification',
                'body' => $noteReminder->note,
                'sound' => 'default',
            ];
        }

        $params = [
            'notifications' => $notifications,
        ];

        PushNotificationJob::dispatch($params);
    }
}
