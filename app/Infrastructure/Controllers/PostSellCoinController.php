<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\CoinDataSource;
use App\Application\DataSources\WalletDataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class PostSellCoinController extends BaseController
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(CoinDataSource $coinDataSource, WalletDataSource $walletDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(Request $body): JsonResponse
    {
        $validator = Validator::make($body->all(), [
            "coin_id" => "required|string",
            "wallet_id" => "required|string",
            "amount_usd" => "required|integer|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'description' => 'bad request error'
            ], Response::HTTP_BAD_REQUEST);
        }

        $coin = $this->coinDataSource->findById($body->input('coin_id'), $body->input('amount_usd'));
        if (is_null($coin)) {
            return response()->json([
                'description' => 'A coin with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $wallet = $this->walletDataSource->findById($body->input('wallet_id'));
        if (is_null($wallet)) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->walletDataSource->sellCoinFromWallet(
            $wallet->getWalletId(),
            $coin,
            $body->input('amount_usd')
        );
        return response()->json([
            'description' => 'successful operation',
        ], Response::HTTP_OK);
    }
}
