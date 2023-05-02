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
     * @param string $coin_id
     * @param string $name
     * @param string $symbol
     * @param float $amount
     * @param float $value_usd
     */
    public function __construct(int $id,
                                string $email,
                                string $coin_id,
                                string $name,
                                string $symbol,
                                float $amount,
                                float $value_usd)
    {
        $this->id = $id;
        $this->email = $email;
        $this->coin_id = $coin_id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->value_usd = $value_usd;
    }
}
