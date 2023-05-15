<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\CoinDataSource;

class FileCoinDataSource implements CoinDataSource
{
    public function findById(string $coinId): ?Coin
    {
        // acceso a la API
        return null;
    }
}
