<?php

namespace App\Infrastructure\Persistence;

use App\Application\DataSources\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

class FileWalletDataSource implements WalletDataSource
{
    public function findById(string $wallet_id): ?Wallet
    {
        $wallet_array = Cache::get('wallet_' . $wallet_id);
        return new Wallet($wallet_array['wallet_id']);
    }

    public function insertCoinInWallet(string $wallet_id, Coin $coin, int $amount): void
    {
        $wallet = Cache::get('wallet_' . $wallet_id);
        array_push($wallet['coins'], $coin->getJsonData());
        Cache::put('wallet_' . $wallet_id, $wallet);
    }
}
