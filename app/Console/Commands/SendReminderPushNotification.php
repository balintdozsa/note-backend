<?php

namespace App\Console\Commands;

use App\Jobs\PushNotificationJob;
use App\Models\NoteReminder;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendReminderPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminderPush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
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
            ];
        }

        $params = [
            'notifications' => $notifications,
        ];

        PushNotificationJob::dispatch($params);
    }
}
