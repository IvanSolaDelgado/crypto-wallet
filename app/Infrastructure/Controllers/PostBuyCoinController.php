<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\UserDataSource\UserDataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class PostBuyCoinController extends BaseController
{
    private CoinDataSource $coinDataSource;

    public function __construct(CoinDataSource $coinDataSource)
    {
        $this->coinDataSource = $coinDataSource;
    }

    public function __invoke(Request $body): JsonResponse
    {
        $validator = Validator::make($body->all(), [
                "coin_id" => "required|string",
                "wallet_id" => "required|string",
                "amount_usd" => "required|integer",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'description' => 'bad request error'
            ], Response::HTTP_BAD_REQUEST);
        }
        $coin = $this->coinDataSource->findById($body->input('coin_id'));
        if (is_null($coin)) {
            return response()->json([
                'description' => 'A coin with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'description' => 'successful operation',
            'wallet_id' => str($coin->getUserId())
        ], Response::HTTP_OK);
    }
}

