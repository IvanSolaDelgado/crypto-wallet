<?php

namespace App\Domain;

class User
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
        $this->coin_id = $coinId;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->value_usd = $valueUsd;
    }
}
