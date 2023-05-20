<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\UserDataSource;
use App\Domain\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class PostOpenWalletController extends BaseController
{
    private UserDataSource $userDataSource;

    public function __construct(UserDataSource $userDataSource)
    {
        $this->userDataSource = $userDataSource;
    }
    public function __invoke(Request $body): JsonResponse
    {
        $validator = Validator::make($body->all(), [
            'user_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
        $user = $this->userDataSource->findById($body->input('user_id'));
        if (is_null($user)) {
            return response()->json([
                'description' => 'A user with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $walletId = $this->saveWalletIncache();
        if ($walletId) {
            return response()->json([
                'description' => 'successful operation',
                'wallet_id' => str($walletId)
            ], Response::HTTP_OK);
        }
        return response()->json([
            'description' => 'cache is full',
        ], Response::HTTP_NOT_FOUND);
    }

    public function saveWalletIncache(): ?string
    {
        for ($i = 1; $i <= 100; $i++) {
            if (!Cache::has('wallet_' . $i)) {
                $wallet = new Wallet('wallet_' . $i);
                $wallet = $wallet->getJsonData();
                Cache::put('wallet_' . $i, $wallet);
                return 'wallet_' . $i;
            }
        }
        return null;
    }
}
