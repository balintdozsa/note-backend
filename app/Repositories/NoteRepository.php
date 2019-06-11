<?php

namespace App\Repositories;

use App\Models\Note;

class NoteRepository extends BaseRepository
{
    protected function getModel() {
        return Note::class;
    }

    public function getByUserId($user_id) {
        $notes = $this->model->where('user_id', $user_id)->orderBy('updated_at', 'desc')->get();

        return $notes;
    }

    public function getByIdAndUserId($id, $user_id) {
        $notes = $this->model->where(['id' => $id, 'user_id' => $user_id])->first();

        return $notes;
    }
}