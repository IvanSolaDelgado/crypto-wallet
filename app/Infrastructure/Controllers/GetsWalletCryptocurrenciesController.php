<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\WalletDataSource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GetsWalletCryptocurrenciesController extends BaseController
{
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }
    public function __invoke($wallet_id): JsonResponse
    {
        $wallet_id = intval($wallet_id);
        $validator = Validator::make(['wallet_id' => $wallet_id], [
            'wallet_id' => 'required|int|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        if (is_null($this->walletDataSource->findById($wallet_id))) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $walletArray = Cache::get('wallet_' . $wallet_id);

        return response()->json([$walletArray['coins']], Response::HTTP_OK);
    }
}
