<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(string $walletId): ?Wallet;
}
