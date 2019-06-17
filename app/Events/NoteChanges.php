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

class NoteChanges
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $note, $timeZone;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Note $note, $timeZone = 'UTC')
    {
        $this->note = $note;
        $this->timeZone = $timeZone;
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
