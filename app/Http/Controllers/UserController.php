<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;
use \GuzzleHttp\Client;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $user = $this->userRepository->getById(Auth::id());

        return response()->json(["data" => $user,]);
    }

    public function setPushToken(Request $request) {
        $token = $request->get('token');

        $this->userRepository->modifyById(Auth::id(), ['push_token' => $token,]);
    }

    public function sendPushNotification(Request $request) {
        $user_id = $request->get('user_id');
        $title = $request->get('title');
        $body = $request->get('body');

        if (empty($user_id) || empty($title) || empty($body)) return response()->json(["status" => "fail"]);

        $push_token = $this->userRepository->getById($user_id)->push_token;

        $client = new Client();
        $response = $client->post('https://exp.host/--/api/v2/push/send', [
            'json' => [
                'to' => 'ExponentPushToken['.$push_token.']',
                'title' => $title,
                'body' => $body,
            ],
            'header' => [
                'Accept' => 'application/json', 'Content-Type' => 'application/json', 'Accept-Encoding' => 'gzip, deflate'
            ],
        ]);

        return response()->json(["status" => "ok", "response" => json_decode($response->getBody()),]);
    }
}