<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private static $wasSetup = false;
    protected static $token = null;

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$wasSetup) {
            self::$token = $this->initDb();
        }

        self::$wasSetup = true;
    }

    private function setupTestDb() {
        // create test environment:
        $this->artisan('config:cache');
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

        print "initDb\n";
        return $content['token'];
    }
}
