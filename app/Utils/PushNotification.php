<?php

namespace App\Utils;

use App\Repositories\UserPushTokenRepository;
use GuzzleHttp\Client;

class PushNotification
{
    public static function send($user_id, $title, $body) {
        if (empty($user_id) || empty($title) || empty($body)) return ["status" => "fail",];

        $userPushTokenRepository = new UserPushTokenRepository();
        $pushTokens = $userPushTokenRepository->getByUserId($user_id);
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
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ]);

        return ["status" => "ok", "response" => json_decode($response->getBody()),];
    }

    public static function sendToAll($notifications = []) {
        $client = new Client();
        $response = $client->post('https://exp.host/--/api/v2/push/send', [
            'json' => $notifications,
            'header' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ]);

        return ["status" => "ok", "response" => json_decode($response->getBody()),];
    }
}