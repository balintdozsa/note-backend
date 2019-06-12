<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;
use App\Repositories\UserPushTokenRepository;
use \GuzzleHttp\Client;
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

        $this->userPushTokenRepository->add(['user_id' => Auth::id(), 'push_token' => $token,]);
    }

    public function sendPushNotification(Request $request) {
        $user_id = $request->post('user_id');
        $title = $request->post('title');
        $body = $request->post('body');

        if (empty($user_id) || empty($title) || empty($body)) return response()->json(["status" => "fail"]);

        //$push_token = $this->userRepository->getById($user_id)->push_token;
        $pushTokens = $this->userPushTokenRepository->getByUserId($user_id);
        $notifications = [];
        foreach ($pushTokens as $pushToken) {
            $notifications[] = [
                'to' => 'ExponentPushToken['.$pushToken->push_token.']',
                'title' => $title,
                'body' => $body,
            ];
        }

        $client = new Client();
        $response = $client->post('https://exp.host/--/api/v2/push/send', [
            'json' => $notifications,
            'header' => [
                'Accept' => 'application/json', 'Content-Type' => 'application/json', 'Accept-Encoding' => 'gzip, deflate'
            ],
        ]);

        return response()->json(["status" => "ok", "response" => json_decode($response->getBody()),]);
    }
}