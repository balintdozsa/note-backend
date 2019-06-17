<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Note;

class ChangeNote
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $note, $timeZone, $del;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Note $note, $timeZone = 'UTC', $del = false)
    {
        $this->note = $note;
        $this->timeZone = $timeZone;
        $this->del = $del;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
