<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private function setupTestDb() {
        // create test environment:
        $this->artisan('migrate:fresh');
        $this->artisan('passport:install');
    }

    protected function initDb() {
        $this->setupTestDb();

        $user = factory(User::class)->create([
            'name' => 'test.user',
            'email' => 'test.user@example.com',
            'password' => bcrypt('test.pw'),
        ]);

        $response = $this->post('/api/login', ['email' => 'test.user@example.com', 'password' => 'test.pw']);

        $content = $response->decodeResponseJson();

        return $content['token'];
    }
}
