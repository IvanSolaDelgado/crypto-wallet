<?php

namespace App\Domain;

class Wallet
{
    private string $walletId;
    private array $coins;
    private int $balance;
    private string $userId;

    public function __construct($walletId)
    {
        $this->walletId = $walletId;
        $this->coins = [];
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }

    public function insertCoin($coin_id, $amount): void
    {
        foreach ($this->coins as $coin) {
            if ($coin->getId() == $coin_id) {
                $coin->incrementAmount($amount);
            }
        }
        if (empty($this->coins)) {
            //esto hace falta consumirlo de la api
            array_push($this->coins, new Coin($coin_id, "name", "symbol", $amount, 1));
        }
    }

    public function getJsonData(): array
    {
        $attributes = get_object_vars($this);
        foreach ($attributes as &$attribute) {
            if (is_array($attribute)) {
                foreach ($attribute as &$coin) {
                    $coin = $coin->getJsonData();
                }
            }
        }
        return $attributes;
    }

    public function setCoins(array $coins): void
    {
        $this->coins = $coins;
    }
}
