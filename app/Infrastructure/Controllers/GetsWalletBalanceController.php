<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\CoinDataSource;
use App\Application\DataSources\WalletDataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GetsWalletBalanceController extends BaseController
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    public function __construct(WalletDataSource $walletDataSource, CoinDataSource $coinDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->coinDataSource = $coinDataSource;
    }
    public function __invoke($wallet_id): JsonResponse
    {
        $validator = Validator::make(['wallet_id' => $wallet_id], [
            'wallet_id' => 'required|int|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        $wallet = $this->walletDataSource->findById($wallet_id);
        if (is_null($wallet)) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $walletArray = Cache::get('wallet_' . $wallet_id);
        $accumulatedSum = $walletArray['BuyTimeAccumulatedValue'];
        $totalValue = 0;

        foreach ($walletArray['coins'] as $coin) {
            $coinCurrentValue = $this->coinDataSource->getUsdValue($coin['coinId']);
            $totalValue += $coinCurrentValue * $coin['amount'];
        }

        $balance = $totalValue - $accumulatedSum;
        return response()->json(['balance_usd' => $balance], Response::HTTP_OK);
    }
}
