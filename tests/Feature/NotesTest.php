<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotesTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->get('/api/notes/1');

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertCount(2, $content);
    }
}
