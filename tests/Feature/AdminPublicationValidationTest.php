<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPublicationValidationTest extends TestCase
{
    use RefreshDatabase;

    private function publications(): array
    {
        return [
            ['news', 'berita_siber', ['title' => 'News', 'description' => 'Description', 'thumbnail' => 'image', 'source' => 'source', 'date' => '2024-01-02'], 'date', 'not-a-date'],
            ['event', 'events', ['title' => 'Event', 'event_date' => '2024-01-02'], 'capacity', 'not-an-integer'],
            ['warning', 'peringatan_keamanan', ['title' => 'Warning', 'description' => 'Description', 'thumbnail' => 'image', 'source' => 'source', 'date' => '2024-01-02'], 'date', 'not-a-date'],
            ['law', 'peraturan_kebijakan', ['title' => 'Law', 'description' => 'Description', 'link' => 'reference', 'date' => '2024-01-02', 'time' => '12:30', 'downloadAmount' => 1], 'time', '25:61'],
            ['guide', 'panduan_teknis', ['title' => 'Guide', 'author' => 'Author', 'link' => 'reference'], 'author', ['invalid']],
            ['infographic', 'infografis_keamanan', ['title' => 'Infographic', 'thumbnail' => 'image'], 'thumbnail', ['invalid']],
        ];
    }

    public function test_publication_create_and_update_preserve_validation_and_redirects(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        config(['app.locale' => 'en']);

        foreach ($this->publications() as [$name, $table, $valid, $invalidField, $invalidValue]) {
            $store = route("admin.{$name}.store");
            $this->from('/admin')->post($store, [])->assertRedirect('/admin')
                ->assertSessionHasErrors(['title' => 'The title field is required.']);
            $this->assertDatabaseCount($table, 0);

            $invalid = array_replace($valid, [$invalidField => $invalidValue]);
            $this->from('/admin')->post($store, $invalid)->assertRedirect('/admin')
                ->assertSessionHasErrors($invalidField)->assertSessionHasInput('title', $valid['title']);
            $this->assertDatabaseCount($table, 0);

            $this->post($store, $valid)->assertRedirect(route('admin.dashboard'))
                ->assertSessionHas('success', ucfirst($name).' added!');
            $this->assertDatabaseHas($table, ['title' => $valid['title']]);
            $id = \DB::table($table)->where('title', $valid['title'])->value('id');
            $update = route("admin.{$name}.update", $id);

            $this->from('/edit')->post($update, [])->assertRedirect('/edit')
                ->assertSessionHasErrors(['title' => 'The title field is required.']);
            $this->from('/edit')->post($update, $invalid)->assertRedirect('/edit')
                ->assertSessionHasErrors($invalidField)->assertSessionHasInput('title', $valid['title']);
            $this->assertDatabaseHas($table, ['id' => $id, 'title' => $valid['title']]);

            $valid['title'] .= ' updated';
            $this->post($update, $valid)->assertRedirect(route('admin.dashboard'))
                ->assertSessionHas('success', ucfirst($name).' updated!');
            $this->assertDatabaseHas($table, ['id' => $id, 'title' => $valid['title']]);
            $this->post(route("admin.{$name}.update", 999999), [])->assertNotFound();
        }
    }

    public function test_nullable_validation_fields_still_return_existing_database_failure_messages(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        foreach ([
            ['news', 'berita_siber', ['title' => 'News', 'description' => 'Description', 'thumbnail' => 'image', 'source' => 'source', 'date' => '2024-01-02'], 'source', 'berita'],
            ['warning', 'peringatan_keamanan', ['title' => 'Warning', 'description' => 'Description', 'thumbnail' => 'image', 'source' => 'source', 'date' => '2024-01-02'], 'source', 'peringatan'],
            ['law', 'peraturan_kebijakan', ['title' => 'Law', 'description' => 'Description', 'link' => 'reference', 'date' => '2024-01-02', 'time' => '12:30', 'downloadAmount' => 1], 'link', 'peraturan'],
        ] as [$name, $table, $valid, $field, $label]) {
            $invalidForDatabase = array_replace($valid, [$field => null]);
            $this->from('/admin')->post(route("admin.{$name}.store"), $invalidForDatabase)
                ->assertRedirect('/admin')->assertSessionHasErrors(['title' => "Gagal menyimpan {$label}. Silakan coba lagi."])
                ->assertSessionHasInput('title', $valid['title']);
            $this->assertDatabaseCount($table, 0);

            $this->post(route("admin.{$name}.store"), $valid)->assertRedirect(route('admin.dashboard'));
            $id = \DB::table($table)->where('title', $valid['title'])->value('id');
            $this->from('/edit')->post(route("admin.{$name}.update", $id), $invalidForDatabase)
                ->assertRedirect('/edit')->assertSessionHasErrors(['title' => "Gagal memperbarui {$label}. Silakan coba lagi."])
                ->assertSessionHasInput('title', $valid['title']);
            $this->assertDatabaseHas($table, ['id' => $id, $field => $valid[$field]]);
        }
    }
}
