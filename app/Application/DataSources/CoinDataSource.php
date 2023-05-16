<?php

namespace App\Application\CoinDataSource;

use App\Domain\Coin;

interface CoinDataSource
{
    public function findById(string $coinId): ?Coin;
}
