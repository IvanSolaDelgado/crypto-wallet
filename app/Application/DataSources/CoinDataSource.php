<?php

namespace App\Application\DataSources;

use App\Domain\Coin;

interface CoinDataSource
{
    public function findById(string $coinId, string $amountUsd): ?Coin;
    public function getUsdValue(string $coinId): ?float;
}
