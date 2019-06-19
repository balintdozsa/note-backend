<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;
use App\Repositories\UserPushTokenRepository;
use Carbon\Carbon;

class UserController extends Controller
{
    private $userRepository;
    private $userPushTokenRepository;

    public function __construct(UserRepository $userRepository, UserPushTokenRepository $userPushTokenRepository) {
        $this->userRepository = $userRepository;
        $this->userPushTokenRepository = $userPushTokenRepository;
    }

    public function index() {
        $user = $this->userRepository->getById(Auth::id());
        $user['push_tokens'] = $user->pushTokens;

        return response()->json(["data" => $user,]);
    }

    public function revokeUserToken() {
        Auth::user()->token()->revoke();
    }

    public function setPushToken(Request $request) {
        $token = $request->post('token');

        $this->userPushTokenRepository->updateOrCreate([
            'push_token' => $token,
        ], [
            'user_id' => Auth::id(),
            'created_at' => Carbon::now(),
        ]);

        return response()->json(["status" => "ok"]);
    }

    public function unsetPushToken(Request $request) {
        $pushToken = $request->post('push_token');

        $this->userPushTokenRepository->deleteByColumns(['push_token' => $pushToken,]);

        return response()->json(["status" => "ok"]);
    }
}