<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(String $walletId): ?Wallet;
}
