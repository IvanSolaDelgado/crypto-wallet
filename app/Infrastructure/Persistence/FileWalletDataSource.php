<?php

namespace App\Infrastructure\Persistence;

use App\Application\DataSources\WalletDataSource;
use App\Domain\Wallet;

class FileWalletDataSource implements WalletDataSource
{
    public function findById(string $userId): ?Wallet
    {
        return new Wallet(0);
    }
}
