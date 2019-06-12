<?php

namespace App\Repositories;

use App\Models\UserPushToken;

class UserPushTokenRepository extends BaseRepository
{
    protected function getModel() {
        return UserPushToken::class;
    }

    public function getByUserId($user_id) {
        $tokens = $this->model->where('user_id', $user_id)->orderBy('created_at', 'asc')->get();
        return $tokens;
    }
}