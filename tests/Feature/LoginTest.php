<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User;

class LoginTest extends TestCase
{
    public function testLoginOk()
    {
        $response = $this->post('/api/login', ['email' => 'test.user@example.com', 'password' => 'test.pw']);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('token', $content);
    }

    public function testLoginFailed()
    {
        $response = $this->post('/api/login', ['email' => 'test.user@example.com', 'password' => 'wrong.pw']);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayNotHasKey('token', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertEquals('failed', $content['status']);
    }
}
