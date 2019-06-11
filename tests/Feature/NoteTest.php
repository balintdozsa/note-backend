<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Note;

class NoteTest extends TestCase
{
    public function testListNote() {
        $response = $this->get('/api/note', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('data', $content);
    }

    public function testListNoteUnauthorized() {
        $response = $this->get('/api/note', ['Authorization' => 'Bearer wrong_token',]);

        $response->assertStatus(401);
    }

    public function testAddNote() {
        $response = $this->post('/api/note/add', ['note' => 'Note01',], ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('status', $content);
        $this->assertEquals('ok', $content['status']);


        $response = $this->post('/api/note/add', ['note' => 'Note02',], ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('status', $content);
        $this->assertEquals('ok', $content['status']);


        $response = $this->get('/api/note', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('data', $content);
        $this->assertCount(2, $content['data']);
    }

    public function testAddNoteUnauthorized() {
        $response = $this->post('/api/note/add', ['note' => 'Note01',], ['Authorization' => 'Bearer wrong_token',]);

        $response->assertStatus(401);
    }

    public function testDeleteNote() {
        $note = factory(Note::class)->create([
            'user_id' => 1,
            'note' => 'Note11',
        ]);

        $response = $this->get('/api/note', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('data', $content);
        $this->assertCount(3, $content['data']);


        $response = $this->post('/api/note/delete', ['id' => $note->id,], ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('status', $content);
        $this->assertEquals('ok', $content['status']);


        $response = $this->get('/api/note', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('data', $content);
        $this->assertCount(2, $content['data']);
    }

    public function testDeleteNoteUnauthorized() {
        $response = $this->post('/api/note/delete', ['id' => 1,], ['Authorization' => 'Bearer wrong_token',]);

        $response->assertStatus(401);
    }

    public function testModifyNote() {
        $note = factory(Note::class)->create([
            'user_id' => 1,
            'note' => 'Note21',
        ]);

        $response = $this->post('/api/note/modify', ['id' => $note->id, 'note' => 'Note25',], ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('status', $content);
        $this->assertEquals('ok', $content['status']);


        $response = $this->get('/api/note', ['Authorization' => 'Bearer '.self::$token,]);

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertArrayHasKey('data', $content);
        foreach ($content['data'] as $item) {
            if ((int)$item['id'] === $note->id) {
                $this->assertEquals('Note25', $item['note']);
            }
        }
    }

    public function testModifyNoteUnauthorized() {
        $response = $this->post('/api/note/modify', ['id' => 1, 'note' => 'Note25',], ['Authorization' => 'Bearer wrong_token',]);

        $response->assertStatus(401);
    }
}
