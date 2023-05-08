<?php

namespace App\Domain;

class Wallet
{
    private int $id;
    private array $coins;
    private int $balance;

    public function __construct($id) {
        $this->id = $id;
    }
}
