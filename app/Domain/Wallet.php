<?php

namespace App\Domain;

class Wallet
{
    private int $walletId;
    private array $coins;
    private int $balance;

    public function __construct($walletId)
    {
        $this->walletId = $walletId;
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }
}
