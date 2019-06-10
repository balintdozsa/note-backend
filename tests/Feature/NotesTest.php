<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Notes;

class NotesTest extends TestCase
{
    public function testListNotes() {
        $response = $this->get('/api/notes', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
    }
}
