<?php

namespace App\Domain;

class Wallet
{
    private int $walleId;
    private array $coins;
    private int $balance;

    public function __construct($walletId)
    {
        $this->walleId = $walletId;
    }

    public function getWalletId(): string
    {
        return $this->walleId;
    }
}
