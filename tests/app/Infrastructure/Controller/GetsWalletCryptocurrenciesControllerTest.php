<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSources\WalletDataSource;
use App\Domain\Wallet;
use App\Domain\Coin;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class GetsWalletCryptocurrenciesControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->app->bind(WalletDataSource::class, function () {
            return $this->walletDataSource;
        });
    }

    /**
     * @test
     */
    public function ifBadWalletIdThrowsBadRequest()
    {
        $this->walletDataSource
            ->expects("findById")
            ->with(null)
            ->times(0)
            ->andReturn(null);

        $response = $this->get('api/wallet/-5');
        $response->assertBadRequest();
    }

    /**
     * @test
     */
    public function ifWalletIdNotFoundThrowsError()
    {
        $wallet = new Wallet('0');
        $this->walletDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(null);

        $response = $this->get('api/wallet/' . $wallet->getWalletId());

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A wallet with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function ifWalletIdExistsGetCoins()
    {
        $walletId = 0;
        $walletCoins = [
            (new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64))->getJsonData(),
            (new Coin('80', 'Ethereum', 'ETH', 10, 1830))->getJsonData(),
            (new Coin('518', 'Tether', 'USDT', 2, 1.00))->getJsonData(),
            (new Coin('2710', 'Binance Coin', 'BNB', 4, 30705))->getJsonData(),
        ];
        $wallet = new Wallet($walletId);

        $this->walletDataSource
            ->expects("findById")
            ->with($walletId)
            ->andReturn($wallet);

        Cache::shouldReceive('get')
            ->with('wallet_' . $walletId)
            ->andReturn(['BuyTimeAccumulatedValue' => 10, 'coins' => $walletCoins]);

        $response = $this->get('api/wallet/' . $wallet->getWalletId());

        $response->assertOk();
        $response->assertJson([$walletCoins]);
    }
}
