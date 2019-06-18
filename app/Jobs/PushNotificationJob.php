<?php

namespace App\Jobs;

use App\Utils\SendPushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $notifications = [];

    public $tries = 12;
    public $retryAfter = 15;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params = [])
    {
        $this->notifications = $params['notifications'] ?? [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_array($this->notifications) && count($this->notifications)) {
            SendPushNotification::sendToAll($this->notifications);
        }
    }
}
