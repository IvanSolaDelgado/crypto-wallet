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

    public function insertCoinInWallet(string $wallet_id, Coin $coin): void
    {
        $wallet = Cache::get('wallet_' . $wallet_id);
        array_push($wallet['coins'], $coin->getJsonData());
        Cache::put('wallet_' . $wallet_id, $wallet);
        print_r($wallet);
        //TODO: Si compro dos veces la misma moneda, se deberian juntar en una sola entrada en la cache ?
        // o deberian ser dos monedas diferenciadas?
        // en caso de ser diferenciadas a la hora de vender esa moneda, cual de las dos venderia?
    }

    public function sellCoinFromWallet(string $wallet_id, Coin $coin, float $updatedUsdValue, string $amountUsd): void
    {
        if (Cache::has('wallet_' . $wallet_id)) {
            $wallet = Cache::get('wallet_' . $wallet_id);
            $itemToUpdate = 0;
            foreach ($wallet['coins'] as $coinItem) {
                if (strcmp($coinItem['coinId'], $coin->getId()) == 0) {
                    $wallet['coins'][$itemToUpdate]['amount'] -= floatval($amountUsd) / $updatedUsdValue;
                    Cache::put('wallet_' . $wallet_id, $wallet);
                    break;
                }
                $itemToUpdate++;
            }
        }
        //TODO: Es posible tener un amount negativo si vendo mas del valor que tengo en monedas
        // o deberia lanzar un exception.
        print_r($wallet);
    }

    public function saveWalletIncache(): ?string
    {
        for ($i = 1; $i <= 100; $i++) {
            if (!Cache::has('wallet_' . $i)) {
                $wallet = new Wallet('wallet_' . $i);
                $wallet = $wallet->getJsonData();
                Cache::put('wallet_' . $i, $wallet);
                return 'wallet_' . $i;
            }
        }
        return null;
    }
}
