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
        if (Cache::has('wallet_' . $wallet_id)) {
            return new Wallet($wallet_id);
        }
        return null;
    }

    public function insertCoinInWallet(string $walletId, Coin $coin): void
    {
        $wallet = Cache::get('wallet_' . $walletId);
        $wallet['BuyTimeAccumulatedValue'] += ($coin->getValueUsd() * $coin->getAmount());
        $inserted = false;
        $coinPositionInWalletArray = 0;
        foreach ($wallet['coins'] as $coinInCache) {
            if ($coinInCache['coinId'] == $coin->getId()) {
                $newAmount = $coinInCache['amount'] + $coin->getAmount();
                $coin->setAmount($newAmount);
                $wallet['coins'][$coinPositionInWalletArray] = $coin->getJsonData();
                $inserted = true;
            }
            $coinPositionInWalletArray++;
        }
        if (!$inserted) {
            array_push($wallet['coins'], $coin->getJsonData());
        }
        Cache::put('wallet_' . $walletId, $wallet);
        $wallet = Cache::get('wallet_0');
    }

    public function sellCoinFromWallet(string $walletId, Coin $coin, float $updatedUsdValue, string $amountUsd): void
    {
        if (Cache::has('wallet_' . $walletId)) {
            $wallet = Cache::get('wallet_' . $walletId);
            $itemToUpdate = 0;
            foreach ($wallet['coins'] as $coinItem) {
                if (strcmp($coinItem['coinId'], $coin->getId()) == 0) {
                    $wallet['coins'][$itemToUpdate]['amount'] -= floatval($amountUsd) / $updatedUsdValue;
                    Cache::put('wallet_' . $walletId, $wallet);
                    break;
                }
                $itemToUpdate++;
            }
        }
    }

    public function saveWalletInCache(): ?string
    {
        for ($i = 0; $i <= 100; $i++) {
            if (!Cache::has('wallet_' . $i)) {
                $wallet = new Wallet('wallet_' . $i);
                $wallet = $wallet->getJsonData();
                $wallet['BuyTimeAccumulatedValue'] = 0;
                Cache::put('wallet_' . $i, $wallet);
                return 'wallet_' . $i;
            }
        }
        return null;
    }
}
