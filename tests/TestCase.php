<?php

namespace Tests;

use App\Models\User;
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
            //config(['database.connections.mysql.database' => 'altair_test']);
            $this->artisan('config:clear');

            self::$token = $this->initDb();
        }

        self::$wasSetup = true;
    }

    private function setupTestDb() {
        // create test environment:
        $this->artisan('migrate:fresh');
        $this->artisan('passport:install');
    }

    protected function initDb() {
        $this->setupTestDb();

        $user = factory(User::class)->create([
            'id' => 1,
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
