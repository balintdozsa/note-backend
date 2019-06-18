<?php

namespace App\Jobs;

use App\Repositories\NoteReminderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        $ids = [];
        $notifications = [];

        $nr = new NoteReminderRepository();
        $nr->getActiveReminders($ids, $notifications);
        $nr->modifyByIds($ids, ['sent' => true,]);

        $params = [
            'notifications' => $notifications,
        ];

        if (count($notifications)) {
            PushNotificationJob::dispatch($params);
        }
    }
}
