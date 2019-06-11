<?php

namespace App\Repositories;

use App\User;

class UserRepository extends BaseRepository
{
    protected function getModel() {
        return User::class;
    }
}