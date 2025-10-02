<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Note;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_notes(): void
    {
        Note::factory()->count(2)->create();
        $res = $this->getJson('/api/notes');
        $res->assertStatus(200)->assertJsonCount(2);
    }

    public function test_create_note(): void
    {
        $payload = ['title' => 'Test', 'content' => 'Hello'];
        $res = $this->postJson('/api/notes', $payload);
        $res->assertStatus(201)->assertJsonFragment(['title' => 'Test']);
    }

    public function test_show_note(): void
    {
        $note = Note::factory()->create();
        $res = $this->getJson('/api/notes/' . $note->id);
        $res->assertStatus(200)->assertJsonFragment(['id' => $note->id]);
    }

    public function test_update_note(): void
    {
        $note = Note::factory()->create();
        $payload = ['title' => 'Updated', 'content' => 'World'];
        $res = $this->putJson('/api/notes/' . $note->id, $payload);
        $res->assertStatus(200)->assertJsonFragment(['title' => 'Updated']);
    }

    public function test_delete_note(): void
    {
        $note = Note::factory()->create();
        $res = $this->deleteJson('/api/notes/' . $note->id);
        $res->assertStatus(200);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
