<?php

namespace App\Application\UserDataSource;

use App\Domain\User;

interface UserDataSource
{
    public function findById(int $id): ?User;
}
