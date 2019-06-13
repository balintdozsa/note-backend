<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;
use App\Repositories\UserPushTokenRepository;
use App\Utils\PushNotification;
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

        return response()->json(["data" => $user,]);
    }

    public function setPushToken(Request $request) {
        $token = $request->post('token');

        //$this->userRepository->modifyById(Auth::id(), ['push_token' => $token,]);

        $this->userPushTokenRepository->updateOrCreate(['push_token' => $token,], ['user_id' => Auth::id(), 'created_at' => Carbon::now(),]);
    }

    public function sendPushNotification(Request $request) {
        $user_id = $request->post('user_id');
        $title = $request->post('title');
        $body = $request->post('body');

        $resp = PushNotification::send($user_id, $title, $body);

        return response()->json($resp);
    }
}