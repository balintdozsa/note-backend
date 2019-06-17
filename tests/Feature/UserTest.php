<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testSetPushToken() {
        $push_token = Str::random(10);

        $response = $this->post('/api/user/setPushToken', ['token' => $push_token,], ['Authorization' => 'Bearer '.self::$token,]);
        $response->assertStatus(200);

        $response = $this->get('/api/user', ['Authorization' => 'Bearer '.self::$token,]);
        $response->assertStatus(200);
        $content = $response->decodeResponseJson();
        $this->assertArrayHasKey('data', $content);
        //$this->assertEquals($push_token, $content['data']['push_token']);
    }
}
