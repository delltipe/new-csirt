<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReadOnlySecurityPreviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);
    }

    public function test_public_read_only_pages_are_accessible(): void
    {
        $this->get('/')->assertOk();
        $this->get('/profile')->assertOk();
        $this->get('/rfc2350')->assertOk();
        $this->get('/publickey')->assertOk();
        $this->get('/statistics')->assertOk();
        $this->get('/news')->assertOk();
        $this->get('/news/1')->assertOk();
        $this->get('/events')->assertOk();
        $this->get('/infographics')->assertOk();
        $this->get('/warnings')->assertOk();
        $this->get('/laws')->assertOk();
        $this->get('/guides')->assertOk();
        $this->get('/search?q=keamanan')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/up')->assertOk();
    }

    public function test_authentication_and_registration_routes_are_forbidden(): void
    {
        $this->get('/register')->assertForbidden();
        $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'Secret123!',
        ])->assertForbidden();

        $this->get('/login')->assertForbidden();
        $this->post('/login', [
            'email' => 'attacker@example.com',
            'password' => 'Secret123!',
        ])->assertForbidden();

        $this->post('/logout')->assertForbidden();
    }

    public function test_bug_hunter_and_incident_reporting_routes_are_forbidden(): void
    {
        $this->get('/bug-hunter')->assertForbidden();
        $this->get('/bug-hunter/laporan')->assertForbidden();
        $this->post('/bug-hunter/laporan/agree')->assertForbidden();
        $this->get('/bug-hunter/laporan/1')->assertForbidden();
        $this->get('/lacak-laporan')->assertForbidden();
    }

    public function test_stage_one_incident_reporting_routes_are_accessible(): void
    {
        $this->get('/bug-hunter/laporan/baru')->assertOk();
        $this->get('/bug-hunter/laporan/selesai')->assertOk();
    }

    public function test_fake_evidence_upload_is_forbidden_and_no_file_created(): void
    {
        Storage::fake('public');

        $initialFileCount = count(Storage::disk('public')->allFiles());
        $this->assertEquals(0, $initialFileCount);

        $fakeFile = UploadedFile::fake()->create('exploit_payload.png', 200, 'image/png');

        $response = $this->from('/bug-hunter/laporan/baru')->post('/bug-hunter/laporan/simpan', [
            'nama_pelapor' => 'Test Reporter',
            'email_pelapor' => 'reporter@example.com',
            'judul_insiden' => 'Test Vulnerability Report',
            'deskripsi' => 'Testing file upload restriction in read-only mode',
            'bukti_file' => $fakeFile,
        ]);

        $response->assertRedirect('/bug-hunter/laporan/baru');
        $response->assertSessionHasErrors();

        // Verify storage remains empty and no upload was written
        Storage::disk('public')->assertMissing('bukti_laporan/exploit_payload.png');
        $this->assertEmpty(Storage::disk('public')->allFiles());

        // Verify database records for incidents and attachments remain zero
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
    }

    public function test_contact_form_submission_does_not_mutate_database(): void
    {
        $initialCount = DB::table('contact_us')->count();
        $this->assertEquals(0, $initialCount);

        $response = $this->from('/contact')->post('/contact', [
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'pesan' => 'Testing read-only contact form',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors('pesan');

        $this->assertEquals(0, DB::table('contact_us')->count());
    }

    public function test_all_admin_routes_return_not_found(): void
    {
        $this->get('/admin/login')->assertNotFound();
        $this->post('/admin/login', ['email' => 'admin@gmail.com', 'password' => '12345678'])->assertNotFound();
        $this->post('/admin/logout')->assertNotFound();
        $this->get('/admin')->assertNotFound();
        $this->get('/admin/incidents')->assertNotFound();
        $this->get('/admin/news')->assertNotFound();
        $this->post('/admin/news', ['title' => 'Malicious News'])->assertNotFound();
        $this->post('/admin/hero', ['judul' => 'Malicious Slide'])->assertNotFound();
        $this->post('/admin/incidents/1/delete')->assertNotFound();
        $this->call('PUT', '/admin/arbitrary/nested/path')->assertNotFound();
        $this->call('DELETE', '/admin/news/1')->assertNotFound();
        $this->call('PATCH', '/admin/settings')->assertNotFound();
    }

    public function test_inventory_of_all_mutating_routes_strictly_prohibits_data_changes(): void
    {
        $router = app('router');
        $mutatingVerbs = ['POST', 'PUT', 'PATCH', 'DELETE'];
        $testedCount = 0;

        foreach ($router->getRoutes() as $route) {
            $methods = array_intersect($route->methods(), $mutatingVerbs);
            if (empty($methods)) {
                continue;
            }

            $uri = $route->uri();
            // Replace optional parameters like {any?}
            $testUri = preg_replace('/\{[^?}]+\?\}/', 'test', $uri);
            // Replace required parameters like {id}
            $testUri = preg_replace('/\{[^}]+\}/', '1', $testUri);
            if (!str_starts_with($testUri, '/')) {
                $testUri = '/' . $testUri;
            }

            foreach ($methods as $method) {
                $response = $this->call($method, $testUri);
                $status = $response->getStatusCode();

                if (str_starts_with($testUri, '/admin')) {
                    $this->assertEquals(404, $status, "Admin route [$method $testUri] must return 404.");
                } elseif ($testUri === '/bug-hunter/laporan/simpan') {
                    $this->assertTrue(in_array($status, [302, 303]), "Demo incident submission route [$method $testUri] must redirect on validation failure.");
                } elseif (str_starts_with($testUri, '/bug-hunter') || in_array($testUri, ['/register', '/login', '/logout'])) {
                    $this->assertEquals(403, $status, "Protected mutating route [$method $testUri] must return 403.");
                } elseif ($testUri === '/contact') {
                    $this->assertTrue(in_array($status, [302, 303]), "Contact route [$method $testUri] must redirect.");
                } else {
                    $this->assertTrue(in_array($status, [403, 404, 405]), "Route [$method $testUri] returned unexpected status $status.");
                }

                $testedCount++;
            }
        }

        $this->assertGreaterThanOrEqual(9, $testedCount, "Expected at least 9 mutating route assertions.");

        // Confirm database remains completely unmutated
        $this->assertEquals(0, DB::table('users')->count());
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
        $this->assertEquals(0, DB::table('tac_agreements')->count());
        $this->assertEquals(0, DB::table('contact_us')->count());
    }

    public function test_mutating_http_verbs_are_rejected_on_public_routes(): void
    {
        $this->put('/news/1')->assertStatus(405);
        $this->delete('/news/1')->assertStatus(405);
        $this->patch('/profile')->assertStatus(405);
        $this->delete('/')->assertStatus(405);
        $this->put('/contact')->assertStatus(405);
    }

    public function test_sensitive_tables_remain_completely_empty(): void
    {
        $this->assertEquals(0, DB::table('users')->count());
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
        $this->assertEquals(0, DB::table('tac_agreements')->count());
        $this->assertEquals(0, DB::table('contact_us')->count());
    }
}
