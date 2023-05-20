<?php

namespace App\Application\DataSources;

use App\Domain\Coin;
use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(string $walletId): ?Wallet;

    public function insertCoinInWallet(string $wallet_id, Coin $coin): void;

    public function sellCoinFromWallet(string $wallet_id, Coin $coin, float $updatedUsdValue, string $amoungUsd): void;
}
