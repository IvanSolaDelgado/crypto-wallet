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
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }
}
