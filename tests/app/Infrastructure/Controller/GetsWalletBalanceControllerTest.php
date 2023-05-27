<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSources\CoinDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class GetsWalletBalanceControllerTest extends TestCase
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

        $response = $this->get('api/wallet/-5/balance');
        $response->assertBadRequest();
    }

    /**
     * @test
     */
    public function ifWalletIdNotFoundThrowsError()
    {
        $walletOne = new Wallet('0');
        $this->walletDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(null);

        $response = $this->get('api/wallet/' . $walletOne->getWalletId() . '/balance');

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A wallet with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function ifThereAreNoProblemsGetsWalletBalance()
    {
        $walletId = '0';
        $wallet = new Wallet($walletId);
        $this->walletDataSource
            ->expects("findById")
            ->with($walletId)
            ->andReturn($wallet);

        $coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () use ($coinDataSource) {
            return $coinDataSource;
        });

        $coinId = 'someCoinId';
        $coinAmount = 5;
        $coinCurrentValue = 20;
        $coinDataSource
            ->expects("getUsdValue")
            ->with($coinId)
            ->andReturn($coinCurrentValue);

        Cache::shouldReceive('get')
            ->with('wallet_' . $walletId)
            ->andReturn(['BuyTimeAccumulatedValue' => 50, 'coins' => [['coinId' => $coinId, 'amount' => $coinAmount]]]);

        $response = $this->get('api/wallet/' . $wallet->getWalletId() . '/balance');

        $expectedBalance = ($coinCurrentValue * $coinAmount) - 50;
        $response->assertOk();
        $response->assertJson(['balance_usd' => $expectedBalance]);
    }
}
