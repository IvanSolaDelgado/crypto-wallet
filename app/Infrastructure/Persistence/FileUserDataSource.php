<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;

class FileUserDataSource implements UserDataSource
{
    public function findById(string $userId): ?User
    {
        return new User(0);
    }
}
