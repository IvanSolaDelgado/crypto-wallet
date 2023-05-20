<?php

namespace Tests\app\Application\DataSources;

use App\Application\DataSources\CoinDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class WalletDataSourceTest extends TestCase
{
    private CoinDataSource $coinDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }

    /**
     * @test
     */
    public function whenHappyPathBuyingCoinPurchaseCached()
    {
        $coin = new Coin(
            "coin_id_value",
            "name_value",
            "symbol_value",
            1,
            1
        );
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn($coin);
        $wallet = new Wallet(-1);
        $wallet = $wallet->getJsonData();
        Cache::put('wallet_-1', $wallet);

        $this->post('api/coin/buy', ["coin_id" => "coin_id_value",
                                                    "wallet_id" => "-1",
                                                    "amount_usd" => 1]);

        $wallet = Cache::get('wallet_-1');
        self::assertEquals($wallet['coins'][0], $coin->getJsonData());
    }
}
