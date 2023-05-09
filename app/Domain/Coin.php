<?php

namespace App\Domain;

class Coin
{
    private string $coinId;
    private string $name;
    private string $symbol;
    private float $amount;
    private float $valueUsd;

    public function __construct(
        string $coinId,
        string $name,
        string $symbol,
        float $amount,
        float $valueUsd
    ) {
        $this->coinId = $coinId;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->valueUsd = $valueUsd;
    }
}
