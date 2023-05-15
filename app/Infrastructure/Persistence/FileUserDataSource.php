<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;

class FileUserDataSource implements UserDataSource
{
    public function findById(String $userId): ?User
    {
        return new User(0);
    }
}
