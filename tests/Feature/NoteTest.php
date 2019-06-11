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
        $this->assertEquals('Note25', $content['data'][count($content['data'])-1]['note']);
    }
}
