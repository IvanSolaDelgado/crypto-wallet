<?php

namespace App\Infrastructure\Controllers;

use App\Application\UserDataSource\UserDataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class PostOpenWalletController extends BaseController
{
    private UserDataSource $userDataSource;

    public function __construct(UserDataSource $userDataSource)
    {
        $this->userDataSource = $userDataSource;
    }


    public function __invoke(Request $body): JsonResponse
    {
        $user = $this->userDataSource->findById($body->input('user_id'));
        if (is_null($user)) {
            return response()->json([
                'description' => 'A user with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'description' => 'successful operation',
            'wallet_id' => str($user->getUserId())
        ], Response::HTTP_OK);
    }
}
