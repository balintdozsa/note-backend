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

    private $notifications = [];
    private $user_id, $title, $body = null;

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
        $this->user_id = $params['user_id'] ?? null;
        $this->title = $params['title'] ?? null;
        $this->body = $params['body'] ?? null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_array($this->notifications) && count($this->notifications)) {
            PushNotification::sendToAll($this->notifications);
        } else {
            PushNotification::send($this->user_id, $this->title, $this->body);
        }
    }
}
