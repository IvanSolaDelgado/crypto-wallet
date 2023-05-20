<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
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
    private WalletDataSource $walletDataSource;

    public function __construct(UserDataSource $userDataSource, WalletDataSource $walletDataSource)
    {
        $this->userDataSource = $userDataSource;
        $this->walletDataSource = $walletDataSource;
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
        $walletId = $this->walletDataSource->saveWalletIncache();
        echo $walletId;
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
}
