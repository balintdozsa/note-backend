<?php

namespace App\Repositories;

use App\Models\NoteReminder;


class NoteReminderRepository extends BaseRepository
{
    protected function getModel() {
        return NoteReminder::class;
    }
}