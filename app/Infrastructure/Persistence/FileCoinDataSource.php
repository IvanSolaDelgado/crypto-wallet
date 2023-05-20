<?php

namespace App\Infrastructure\Persistence;

use App\Application\DataSources\CoinDataSource;
use App\Domain\Coin;

class FileCoinDataSource implements CoinDataSource
{
    public function findById(string $coinId, string $amountUsd): ?Coin
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinlore.net/api/ticker/?id=' . $coinId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $coin_data = json_decode($response, true)[0];
            return new Coin(
                $coin_data["id"],
                $coin_data["name"],
                $coin_data["symbol"],
                floatval($amountUsd) / floatval($coin_data["price_usd"]),
                $coin_data["price_usd"]
            );
        }
        return null;
    }

    public function getUsdValue(string $coinId): ?float
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinlore.net/api/ticker/?id=' . $coinId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $coin_data = json_decode($response, true)[0];
            return floatval($coin_data["price_usd"]);
        }
        return null;
    }
}
