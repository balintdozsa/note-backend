<?php

namespace App\Repositories;

use App\Models\Notes;

class NotesRepository extends BaseRepository
{
    protected function getModel() {
        return Notes::class;
    }

    public function getByUserId($user_id) {
        $notes = $this->model->where('user_id', $user_id)->get();

        return $notes;
    }
}