<?php

namespace App\Application\CoinDataSource;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(string $walletId): ?Wallet;
}
