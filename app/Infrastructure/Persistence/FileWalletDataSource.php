<?php

namespace App\Infrastructure\Persistence;

use App\Application\CoinDataSource\WalletDataSource;
use App\Domain\Wallet;

class FileWalletDataSource implements WalletDataSource
{
    public function findById(string $walletId): ?Wallet
    {
        // TODO: buscar en la cache si existe la wallet con el id especificado.
        return null;
    }
}
