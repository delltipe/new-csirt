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

    public function test_authentication_and_registration_routes_render_safe_demo_pages_and_reject_credentials(): void
    {
        // Safe preview pages render successfully with simulation notices
        $loginResponse = $this->get('/login');
        $loginResponse->assertOk();
        $loginResponse->assertSee('Lanjut sebagai Pelapor Demo');
        $loginResponse->assertSee('Lingkungan Simulasi Publik');
        $loginResponse->assertSee('Admin Sandbox');

        $registerResponse = $this->get('/register');
        $registerResponse->assertOk();
        $registerResponse->assertSee('Pendaftaran Akun Dinonaktifkan');
        $registerResponse->assertSee('Lanjut sebagai Pelapor Demo');

        // Mutating credential operations strictly forbidden
        $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'Secret123!',
        ])->assertForbidden();

        $this->post('/login', [
            'email' => 'attacker@example.com',
            'password' => 'Secret123!',
        ])->assertForbidden();

        $this->post('/logout')->assertForbidden();

        // Confirm no user record was created
        $this->assertEquals(0, DB::table('users')->count());
    }

    public function test_restricted_legacy_incident_routes_remain_blocked(): void
    {
        $this->get('/bug-hunter/laporan/1')->assertForbidden();
    }

    public function test_stage_one_incident_reporting_routes_are_accessible(): void
    {
        $this->get('/bug-hunter')->assertRedirect(route('login'));
        $this->get('/bug-hunter/laporan')->assertOk();
        $this->get('/bug-hunter/laporan/baru')->assertOk();
        $this->get('/bug-hunter/laporan/selesai')->assertOk();
        $this->get('/lacak-laporan')->assertOk();
    }

    public function test_homepage_and_article_incident_reporting_links_reach_usable_flow(): void
    {
        // Homepage contains links to reporting entry and ticket tracking
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee(route('bug-hunter.dashboard'));
        $homeResponse->assertSee(route('ticket.track'));

        // News detail and warning detail CTA lead into reporting flow
        $newsResponse = $this->get('/news/1');
        $newsResponse->assertOk();
        $newsResponse->assertSee(route('bug-hunter.dashboard'));

        $warningResponse = $this->get('/warnings/1');
        $warningResponse->assertOk();
        $warningResponse->assertSee(route('bug-hunter.dashboard'));

        // Following the reporting CTA brings visitor to the safe demo entry page
        $this->get(route('bug-hunter.dashboard'))->assertRedirect(route('login'));
    }

    public function test_simulated_reporter_tac_acknowledgement_flow(): void
    {
        // Visiting terms page shows simulation conditions
        $tacResponse = $this->get('/bug-hunter/laporan');
        $tacResponse->assertOk();
        $tacResponse->assertSee('Syarat &amp; Ketentuan Simulasi', false);
        $tacResponse->assertSee('Saya Setuju & Lanjutkan');

        // Agreeing requires checkbox validation
        $emptyResponse = $this->post('/bug-hunter/laporan/agree', []);
        $emptyResponse->assertRedirect();
        $emptyResponse->assertSessionHasErrors('agree');

        // Agreeing with checkbox sets session and redirects to form without touching database
        $agreeResponse = $this->post('/bug-hunter/laporan/agree', ['agree' => '1']);
        $agreeResponse->assertRedirect(route('bug-hunter.create'));
        $agreeResponse->assertSessionHas('demo_tac_agreed', true);

        $this->assertEquals(0, DB::table('tac_agreements')->count());
        $this->assertEquals(0, DB::table('users')->count());
    }

    public function test_demo_entry_page_and_footer_expose_sandbox_admin_login(): void
    {
        $loginResponse = $this->get('/login');
        $loginResponse->assertOk();
        $loginResponse->assertSee(route('sandbox-admin.login'));
        $loginResponse->assertSee('Admin Sandbox');

        $footerHtml = view('components.footer')->render();
        $this->assertStringContainsString(route('sandbox-admin.login'), $footerHtml);
        $this->assertStringContainsString('Admin Sandbox', $footerHtml);
    }

    public function test_public_navbar_points_cta_to_login_and_includes_tracking_link(): void
    {
        $navbarHtml = view('components.navbar')->render();

        $this->assertStringContainsString(route('login'), $navbarHtml);
        $this->assertStringContainsString('Lapor Insiden Siber', $navbarHtml);
        $this->assertStringContainsString(route('ticket.track'), $navbarHtml);
        $this->assertStringContainsString('Lacak Tiket', $navbarHtml);
    }

    public function test_public_footer_exposes_sandbox_admin_and_ticket_tracking(): void
    {
        $footerHtml = view('components.footer')->render();

        $this->assertStringContainsString(route('sandbox-admin.login'), $footerHtml);
        $this->assertStringContainsString('Admin Sandbox', $footerHtml);
        $this->assertStringContainsString(route('ticket.track'), $footerHtml);
        $this->assertStringContainsString('Lacak Status Tiket', $footerHtml);
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
                } elseif (in_array($testUri, ['/bug-hunter/laporan/simpan', '/bug-hunter/laporan/agree'])) {
                    $this->assertTrue(in_array($status, [302, 303]), "Demo incident submission/terms route [$method $testUri] must redirect on validation failure.");
                } elseif (str_starts_with($testUri, '/bug-hunter') || in_array($testUri, ['/register', '/login', '/logout'])) {
                    $this->assertEquals(403, $status, "Protected mutating route [$method $testUri] must return 403.");
                } elseif ($testUri === '/contact') {
                    $this->assertTrue(in_array($status, [302, 303]), "Contact route [$method $testUri] must redirect.");
                } elseif (str_starts_with($testUri, '/sandbox-admin')) {
                    $this->assertTrue(in_array($status, [302, 303, 403]), "Sandbox admin route [$method $testUri] must redirect or be forbidden when unauthenticated.");
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
