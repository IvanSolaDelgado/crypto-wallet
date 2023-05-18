<?php

namespace App\Application\DataSources;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(string $walletId): ?Wallet;
}
