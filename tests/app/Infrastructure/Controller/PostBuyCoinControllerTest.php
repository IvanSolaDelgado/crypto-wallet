<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Tests\TestCase;

class PostBuyCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
        $this->app->bind(WalletDataSource::class, function () {
            return $this->walletDataSource;
        });
    }

    /**
     * @test
     */
    public function ifCoinIdNotFoundThrowsError()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->andReturn(null);

        $response = $this->post('api/coin/buy', ["coin_id" => "coin_id_value",
                                                      "wallet_id" => "wallet_id_value",
                                                      "amount_usd" => 1]);

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function ifBadRequestThrowsError()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->times(0)
            ->andReturn(null);

        $response = $this->post('api/coin/buy', ["coin_id" => null,
                                                    "wallet_id" => null,
                                                    "amount_usd" => null]);

        $response->assertBadRequest();
        $response->assertExactJson(['description' => 'bad request error']);
    }

    /**
     * @test
     */
    public function whenHappyPathOkReturned()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->andReturn(new Coin(
                "coin_id_value",
                "name_value",
                "symbol_value",
                1,
                1
            ));
        $this->walletDataSource
            ->expects("findById")
            ->with("wallet_id_value")
            ->andReturn(new Wallet("wallet_id_value"));

        $response = $this->post('api/coin/buy', ["coin_id" => "coin_id_value",
                                                    "wallet_id" => "wallet_id_value",
                                                    "amount_usd" => 1]);


        $response->assertOk();
        $response->assertExactJson(['description' => 'successful operation']);
    }
}
