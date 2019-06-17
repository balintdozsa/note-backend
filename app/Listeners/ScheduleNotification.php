<?php

namespace App\Listeners;

use App\Events\NoteChanges;
use App\Repositories\NoteReminderRepository;
use App\Utils\TimeRecognition;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleNotification
{
    private $noteReminderRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(NoteReminderRepository $noteReminderRepository)
    {
        $this->noteReminderRepository = $noteReminderRepository;
    }

    /**
     * Handle the event.
     *
     * @param  NoteChanges  $event
     * @return void
     */
    public function handle(NoteChanges $event)
    {
        $recognizedTimes = TimeRecognition::run($event->note->note, $event->timeZone);
        $this->noteReminderRepository->addReminders($event->note->id, $recognizedTimes, $event->timeZone);
    }
}
