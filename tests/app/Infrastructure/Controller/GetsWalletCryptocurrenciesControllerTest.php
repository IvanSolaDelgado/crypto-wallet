<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSources\WalletDataSource;
use App\Domain\Wallet;
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
        $walletOne = new Wallet('0');
        $this->walletDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(null);

        $response = $this->get('api/wallet/'.$walletOne->getWalletId());

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A wallet with the specified ID was not found']);
    }
}
