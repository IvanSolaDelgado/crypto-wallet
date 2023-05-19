<?php

namespace App\Infrastructure\Persistence;

use App\Application\DataSources\CoinDataSource;
use App\Domain\Coin;

class FileCoinDataSource implements CoinDataSource
{
    public function findById(string $coinId): ?Coin
    {
        // acceso a la API
        return null;
    }
}
