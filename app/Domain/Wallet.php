<?php

namespace App\Domain;

class Wallet
{
    private string $id;
    private array $coins;
    private int $balance;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
