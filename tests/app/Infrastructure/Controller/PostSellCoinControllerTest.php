<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSources\CoinDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Tests\TestCase;

class PostSellCoinControllerTest extends TestCase
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
    public function ifBadRequestThrowsError()
    {
        $response = $this->post('api/coin/sell', ["coin_id" => null,
                                                    "wallet_id" => null,
                                                    "amount_usd" => null]);

        $response->assertBadRequest();
        $response->assertExactJson(['description' => 'bad request error']);
    }

    /**
     * @test
     */
    public function ifCoinIdNotFoundThrowsError()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn(null);

        $response = $this->post('api/coin/sell', ["coin_id" => "coin_id_value",
            "wallet_id" => "wallet_id_value",
            "amount_usd" => 1]);

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A coin with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function ifWalletIdNotFoundThrowsError()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn(new Coin("coinId", "name", "symbol", 2.0, 1.0));
        $this->walletDataSource
            ->expects("findById")
            ->with("walletId")
            ->andReturn(null);

        $response = $this->post('api/coin/sell', ["coin_id" => "coin_id_value",
            "wallet_id" => "walletId",
            "amount_usd" => 1]);


        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A wallet with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function ifCoinWasSoldCorrectlyReturnSuccessful()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn($coin = new Coin(
                "coin_id_value",
                "name_value",
                "symbol_value",
                1,
                1
            ));
        $this->walletDataSource
            ->expects("findById")
            ->with("walletId")
            ->andReturn(new Wallet("walletId"));
        $this->walletDataSource
            ->expects("sellCoinFromWallet")
            ->with("walletId", $coin, 1);

        $response = $this->post('api/coin/sell', ["coin_id" => "coin_id_value",
            "wallet_id" => "walletId",
            "amount_usd" => 1]);

        $response->assertOk();
        $response->assertExactJson(['description' => 'successful operation']);
    }
}
