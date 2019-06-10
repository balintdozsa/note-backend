<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Notes;

class NotesTest extends TestCase
{
    public function testListNotes() {
        $token = $this->initDb();

        $response = $this->get('/api/notes', ['Authorization' => 'Bearer '.$token,]);

        $response->assertStatus(200);
    }
}
