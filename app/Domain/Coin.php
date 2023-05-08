<?php

namespace App\Domain;

class User
{
    private int $id;
    private string $email;

    private string $coin_id;
    private string $name;
    private string $symbol;
    private float $amount;
    private float $value_usd;

    /**
     * @param int $id
     * @param string $email
     * @param string $coinId
     * @param string $name
     * @param string $symbol
     * @param float $amount
     * @param float $valueUsd
     */
    public function __construct(
        int $id,
        string $email,
        string $coinId,
        string $name,
        string $symbol,
        float $amount,
        float $valueUsd
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->coin_id = $coinId;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->value_usd = $valueUsd;
    }
}
