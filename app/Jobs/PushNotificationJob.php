<?php

namespace App\Jobs;

use App\Utils\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id, $title, $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $title, $body)
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resp = PushNotification::send($this->user_id, $this->title, $this->body);
    }
}
