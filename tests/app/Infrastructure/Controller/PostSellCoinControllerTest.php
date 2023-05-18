<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\CoinDataSource\WalletDataSource;
use App\Application\DataSources\UserDataSource;
use App\Domain\Coin;
use App\Domain\User;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
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
            ->with("coin_id_value")
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
            ->with("coin_id_value")
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
    public function ifCoinWasSoldSuccessfully()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->andReturn(new Coin("coin_id_value", "name", "symbol", 10, 10));
        $this->walletDataSource
            ->expects("findById")
            ->with("walletId")
            ->andReturn(new Wallet("walletId"));


        $response = $this->post('api/coin/sell', ["coin_id" => "coin_id_value",
            "wallet_id" => "walletId",
            "amount_usd" => 1]);


        $response->assertOk();
        $response->assertExactJson(['description' => 'successful operation']);
    }
}
