<?php

namespace Tests\app\Application\DataSources;

use App\Application\DataSources\CoinDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileWalletDataSource;
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
            "90",
            "Bitcoin",
            "BTC",
            1,
            1
        );
        $this->coinDataSource
            ->expects("findById")
            ->with("90", "1")
            ->andReturn($coin);
        if (!Cache::has('wallet_0')) {
            $walletDataSource = new FileWalletDataSource();
            $walletDataSource->saveWalletInCache();
        }

        $this->post('api/coin/buy', ["coin_id" => "90",
                                                    "wallet_id" => "0",
                                                    "amount_usd" => 1]);

        $wallet = Cache::get('wallet_0');
        self::assertEquals($wallet['coins'][0], $coin->getJsonData());
    }
}
