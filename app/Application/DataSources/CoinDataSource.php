<?php

namespace App\Application\DataSources;

use App\Domain\Coin;

interface CoinDataSource
{
    public function findById(string $coinId): ?Coin;
}
