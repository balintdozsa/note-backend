<?php

namespace App\Utils;

use GuzzleHttp\Client;

class SendPushNotification
{
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