<?php

namespace App\Application\DataSources;

use App\Domain\User;

interface UserDataSource
{
    public function findById(int $userId): ?User;
}
