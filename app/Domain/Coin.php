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

    public function getJsonData()
    {
        $var = get_object_vars($this);
        return $var;
    }

    public function incrementAmount($difference)
    {
        $this->amount += $difference;
    }

    public function decreaseAmount($difference)
    {
        $this->amount -= $difference;
    }

    public function getId()
    {
        return $this->coinId;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function getValueUsd()
    {
        return $this->valueUsd;
    }
}
