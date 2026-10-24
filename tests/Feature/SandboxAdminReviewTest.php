<?php

namespace Tests\Feature;

use App\Models\SandboxIncident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SandboxAdminReviewTest extends TestCase
{
    use RefreshDatabase;

    protected string $adminUsername = 'preview_admin';
    protected string $adminPassword = 'ValidStrongPassword2026!';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);

        // Ensure the sandbox migration is run on the sandbox connection (in-memory SQLite test database)
        if (!Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->up();
        }

        // Configure test credentials
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => Hash::make($this->adminPassword),
        ]);
    }

    protected function tearDown(): void
    {
        if (Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->down();
        }

        parent::tearDown();
    }

    protected function createSandboxIncident(array $overrides = []): SandboxIncident
    {
        return SandboxIncident::create(array_merge([
            'tiket_no' => SandboxIncident::generateTiketNo(),
            'kategori_insiden' => 'Website Defacement',
            'waktu_kejadian' => now()->subHours(2),
            'lokasi_url' => 'https://demo-target.jakarta.go.id/halaman-uji',
            'down_time' => '01:30',
            'deskripsi' => 'Kronologi simulasi defacement pengujian sandbox.',
            'tindakan_teknis' => 'Restorasi dari cadangan dan penguatan filter input.',
            'bukti_urls' => ['https://example.com/bukti-1', 'https://example.com/bukti-2'],
            'status' => SandboxIncident::STATUS_PENDING,
        ], $overrides));
    }

    public function test_login_page_renders_with_disclaimer_and_no_credentials_leaked(): void
    {
        $response = $this->get('/sandbox-admin/login');
        $response->assertOk();
        $response->assertSee('Administrator Sandbox');
        $response->assertSee('Mode Pratinjau Terbatas');
        $response->assertSee('Nama Pengguna Administrator');
        $response->assertSee('Kata Sandi Administrator');

        // Ensure real or configured password hashes are never present in HTML
        $response->assertDontSee($this->adminPassword);
        $response->assertDontSee(config('services.sandbox_admin.password_hash'));
    }

    public function test_login_succeeds_with_valid_configured_credentials(): void
    {
        $response = $this->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);

        $response->assertRedirect('/sandbox-admin/incidents');
        $this->assertTrue(session('sandbox_admin_authenticated', false));
    }

    public function test_incorrect_credentials_are_rejected(): void
    {
        // 1. Wrong password
        $responseWrongPass = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => 'WrongPassword123!',
        ]);
        $responseWrongPass->assertRedirect('/sandbox-admin/login');
        $responseWrongPass->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 2. Wrong username
        $responseWrongUser = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => 'wrong_admin_user',
            'password' => $this->adminPassword,
        ]);
        $responseWrongUser->assertRedirect('/sandbox-admin/login');
        $responseWrongUser->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 3. Missing fields
        $responseEmpty = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', []);
        $responseEmpty->assertRedirect('/sandbox-admin/login');
        $responseEmpty->assertSessionHasErrors(['username', 'password']);
        $this->assertFalse(session('sandbox_admin_authenticated', false));
    }

    public function test_login_fails_closed_when_credentials_are_missing_or_empty(): void
    {
        // 1. Username is null in configuration
        config(['services.sandbox_admin.username' => null]);
        $resNullUser = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resNullUser->assertRedirect('/sandbox-admin/login');
        $resNullUser->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 2. Password hash is null in configuration
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => null,
        ]);
        $resNullHash = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resNullHash->assertRedirect('/sandbox-admin/login');
        $resNullHash->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 3. Username is empty string in configuration
        config([
            'services.sandbox_admin.username' => '',
            'services.sandbox_admin.password_hash' => Hash::make($this->adminPassword),
        ]);
        $resEmptyUser = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resEmptyUser->assertRedirect('/sandbox-admin/login');
        $resEmptyUser->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 4. Password hash is empty string in configuration
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => '',
        ]);
        $resEmptyHash = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resEmptyHash->assertRedirect('/sandbox-admin/login');
        $resEmptyHash->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));
    }

    public function test_login_fails_closed_when_credentials_are_whitespace_or_malformed(): void
    {
        // 1. Whitespace-only username in configuration
        config([
            'services.sandbox_admin.username' => '   ',
            'services.sandbox_admin.password_hash' => Hash::make($this->adminPassword),
        ]);
        $resWsUser = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resWsUser->assertRedirect('/sandbox-admin/login');
        $resWsUser->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 2. Whitespace-only password hash in configuration
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => '   ',
        ]);
        $resWsHash = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resWsHash->assertRedirect('/sandbox-admin/login');
        $resWsHash->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 3. Password hash is plaintext string (not a valid cryptographic hash)
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => 'PlaintextUnencryptedPassword123!',
        ]);
        $resPlaintext = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => 'PlaintextUnencryptedPassword123!',
        ]);
        $resPlaintext->assertRedirect('/sandbox-admin/login');
        $resPlaintext->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // 4. Password hash is corrupted/malformed bcrypt structure
        config([
            'services.sandbox_admin.username' => $this->adminUsername,
            'services.sandbox_admin.password_hash' => '$2y$04$tooshort',
        ]);
        $resMalformed = $this->from('/sandbox-admin/login')->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);
        $resMalformed->assertRedirect('/sandbox-admin/login');
        $resMalformed->assertSessionHasErrors('username');
        $this->assertFalse(session('sandbox_admin_authenticated', false));
    }

    public function test_administrator_routes_cannot_be_accessed_without_authentication(): void
    {
        $incident = $this->createSandboxIncident();

        // 1. List
        $this->get('/sandbox-admin/incidents')->assertRedirect('/sandbox-admin/login');

        // 2. Show
        $this->get("/sandbox-admin/incidents/{$incident->id}")->assertRedirect('/sandbox-admin/login');

        // 3. Status update
        $this->patch("/sandbox-admin/incidents/{$incident->id}/status", [
            'status' => 'divalidasi',
        ])->assertRedirect('/sandbox-admin/login');

        // 4. Logout
        $this->post('/sandbox-admin/logout')->assertRedirect('/sandbox-admin/login');
    }

    public function test_login_rate_limiting_works(): void
    {
        // 5 attempts allowed per minute
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/sandbox-admin/login', [
                'username' => 'wrong_admin',
                'password' => 'wrong_pass',
            ]);
            $response->assertRedirect();
        }

        // 6th attempt within the same minute should be rate limited (429)
        $response6 = $this->post('/sandbox-admin/login', [
            'username' => 'wrong_admin',
            'password' => 'wrong_pass',
        ]);
        $response6->assertStatus(429);
    }

    public function test_unauthenticated_logout_is_rejected_by_middleware(): void
    {
        $response = $this->post('/sandbox-admin/logout');
        $response->assertRedirect('/sandbox-admin/login');
    }

    public function test_authenticated_logout_invalidates_session_and_clears_auth(): void
    {
        $response = $this->withSession(['sandbox_admin_authenticated' => true])
            ->post('/sandbox-admin/logout');

        $response->assertRedirect('/sandbox-admin/login');
        $this->assertFalse(session('sandbox_admin_authenticated', false));

        // Subsequent access to protected routes is blocked
        $this->get('/sandbox-admin/incidents')->assertRedirect('/sandbox-admin/login');
    }

    public function test_incident_review_flow_is_scoped_to_sandbox_records(): void
    {
        $incident = $this->createSandboxIncident([
            'tiket_no' => 'DEMO-2026-B7TGZTCI',
            'kategori_insiden' => 'Phishing',
            'lokasi_url' => 'https://login-palsu.jakarta.go.id/masuk',
            'deskripsi' => 'Laporan uji coba Phishing di lingkungan sandbox.',
        ]);

        $responseList = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get('/sandbox-admin/incidents');
        $responseList->assertOk();
        $responseList->assertSee('DEMO-2026-B7TGZTCI');
        $responseList->assertSee('Phishing');
        $responseList->assertSee('Menunggu Validasi');

        $responseDetail = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$incident->id}");
        $responseDetail->assertOk();
        $responseDetail->assertSee('DEMO-2026-B7TGZTCI');
        $responseDetail->assertSee('https://login-palsu.jakarta.go.id/masuk');
        $responseDetail->assertSee('Laporan uji coba Phishing di lingkungan sandbox.');

        // Verify showcase database remains unmutated and clean
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
        $this->assertEquals(0, DB::table('users')->count());
    }

    public function test_status_updates_accept_only_permitted_transitions(): void
    {
        $incident = $this->createSandboxIncident(['status' => 'menunggu_validasi']);

        // 1. Valid transition: menunggu_validasi -> divalidasi
        $response1 = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'divalidasi',
            ]);
        $response1->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $response1->assertSessionHas('success');

        $incident->refresh();
        $this->assertEquals('divalidasi', $incident->status);

        // 2. Valid transition: divalidasi -> ditindaklanjuti
        $response2 = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'ditindaklanjuti',
            ]);
        $response2->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $incident->refresh();
        $this->assertEquals('ditindaklanjuti', $incident->status);

        // 3. Valid transition: ditindaklanjuti -> dipulihkan
        $response3 = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'dipulihkan',
            ]);
        $response3->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $incident->refresh();
        $this->assertEquals('dipulihkan', $incident->status);

        // 4. Valid transition: dipulihkan -> selesai
        $response4 = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'selesai',
            ]);
        $response4->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $incident->refresh();
        $this->assertEquals('selesai', $incident->status);
    }

    public function test_status_updates_reject_invalid_transitions_and_unsupported_values(): void
    {
        $incident = $this->createSandboxIncident(['status' => 'menunggu_validasi']);

        // 1. Invalid jump transition: menunggu_validasi -> selesai (not permitted directly)
        $responseJump = $this->withSession(['sandbox_admin_authenticated' => true])
            ->from("/sandbox-admin/incidents/{$incident->id}")
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'selesai',
            ]);
        $responseJump->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $responseJump->assertSessionHasErrors('status');

        $incident->refresh();
        $this->assertEquals('menunggu_validasi', $incident->status);

        // 2. Bogus/unsupported status value
        $responseBogus = $this->withSession(['sandbox_admin_authenticated' => true])
            ->from("/sandbox-admin/incidents/{$incident->id}")
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'unsupported_status_code',
            ]);
        $responseBogus->assertRedirect("/sandbox-admin/incidents/{$incident->id}");
        $responseBogus->assertSessionHasErrors('status');

        $incident->refresh();
        $this->assertEquals('menunggu_validasi', $incident->status);

        // 3. Terminal state cannot transition anywhere
        $terminalIncident = $this->createSandboxIncident(['status' => 'ditolak']);
        $responseTerminal = $this->withSession(['sandbox_admin_authenticated' => true])
            ->from("/sandbox-admin/incidents/{$terminalIncident->id}")
            ->patch("/sandbox-admin/incidents/{$terminalIncident->id}/status", [
                'status' => 'divalidasi',
            ]);
        $responseTerminal->assertRedirect("/sandbox-admin/incidents/{$terminalIncident->id}");
        $responseTerminal->assertSessionHasErrors('status');

        $terminalIncident->refresh();
        $this->assertEquals('ditolak', $terminalIncident->status);
    }

    public function test_other_incident_fields_cannot_be_modified_through_status_endpoint(): void
    {
        $incident = $this->createSandboxIncident([
            'tiket_no' => 'DEMO-2026-IMMUTABLE',
            'kategori_insiden' => 'Website Defacement',
            'lokasi_url' => 'https://example.com/original-url',
            'down_time' => '02:00',
            'deskripsi' => 'Deskripsi orisinal tidak boleh berubah.',
            'tindakan_teknis' => 'Tindakan orisinal tidak boleh berubah.',
            'status' => 'menunggu_validasi',
        ]);

        $payload = [
            'status' => 'divalidasi',
            'tiket_no' => 'DEMO-2026-HACKED',
            'kategori_insiden' => 'SQL Injection',
            'lokasi_url' => 'https://malicious.example.com/',
            'down_time' => '99:99',
            'deskripsi' => 'Upaya modifikasi ilegal pada deskripsi.',
            'tindakan_teknis' => 'Upaya modifikasi ilegal pada tindakan.',
        ];

        $response = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", $payload);

        $response->assertRedirect("/sandbox-admin/incidents/{$incident->id}");

        $incident->refresh();
        // Permitted status was updated
        $this->assertEquals('divalidasi', $incident->status);

        // All other attributes MUST remain strictly unchanged
        $this->assertEquals('DEMO-2026-IMMUTABLE', $incident->tiket_no);
        $this->assertEquals('Website Defacement', $incident->kategori_insiden);
        $this->assertEquals('https://example.com/original-url', $incident->lokasi_url);
        $this->assertEquals('02:00', $incident->down_time);
        $this->assertEquals('Deskripsi orisinal tidak boleh berubah.', $incident->deskripsi);
        $this->assertEquals('Tindakan orisinal tidak boleh berubah.', $incident->tindakan_teknis);
    }

    public function test_untrusted_incident_text_is_escaped_in_review_interface(): void
    {
        $incident = $this->createSandboxIncident([
            'tiket_no' => 'DEMO-2026-XSSPAYLOAD',
            'kategori_insiden' => 'Website Defacement',
            'deskripsi' => '<script>alert("xss-in-deskripsi")</script>',
            'tindakan_teknis' => '<img src=x onerror=alert("xss-in-tindakan")>',
            'lokasi_url' => 'https://example.com/?query=<script>alert("xss-url")</script>',
            'status' => 'menunggu_validasi',
        ]);

        $response = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$incident->id}");

        $response->assertOk();

        // Raw HTML script tags must NOT be present unescaped
        $response->assertDontSee('<script>alert("xss-in-deskripsi")</script>', false);
        $response->assertDontSee('<img src=x onerror=alert("xss-in-tindakan")>', false);

        // HTML entity escaped versions MUST be rendered
        $response->assertSee('&lt;script&gt;alert(&quot;xss-in-deskripsi&quot;)&lt;/script&gt;', false);
        $response->assertSee('&lt;img src=x onerror=alert(&quot;xss-in-tindakan&quot;)&gt;', false);
    }

    public function test_dangerous_or_disallowed_url_schemes_are_rendered_as_non_clickable_text(): void
    {
        $dangerousIncident = $this->createSandboxIncident([
            'tiket_no' => 'DEMO-2026-DANGEROUSURL',
            'lokasi_url' => 'javascript:alert("pwned_lokasi")',
            'bukti_urls' => [
                'data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==',
                'vbscript:msgbox("xss")',
                'file:///etc/passwd',
            ],
            'status' => 'menunggu_validasi',
        ]);

        $response = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$dangerousIncident->id}");

        $response->assertOk();

        // Must NOT render clickable <a> tags for dangerous schemes
        $response->assertDontSee('<a href="javascript:', false);
        $response->assertDontSee('<a href="data:', false);
        $response->assertDontSee('<a href="vbscript:', false);
        $response->assertDontSee('<a href="file:', false);

        // Disallowed URLs must be rendered safely as escaped non-clickable text
        $response->assertSee('javascript:alert(&quot;pwned_lokasi&quot;)', false);
        $response->assertSee('data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==', false);
        $response->assertSee('vbscript:msgbox(&quot;xss&quot;)', false);
        $response->assertSee('file:///etc/passwd', false);

        // Verify stored database record remains intact and unmodified
        $dangerousIncident->refresh();
        $this->assertEquals('javascript:alert("pwned_lokasi")', $dangerousIncident->lokasi_url);
        $this->assertCount(3, $dangerousIncident->bukti_urls);
    }

    public function test_valid_http_and_https_urls_remain_clickable_with_target_blank(): void
    {
        $validIncident = $this->createSandboxIncident([
            'tiket_no' => 'DEMO-2026-VALIDURLS',
            'lokasi_url' => 'HTTPS://portal.jakarta.go.id/halaman-resmi',
            'bukti_urls' => [
                'http://example.com/bukti-satu',
                'https://example.com/bukti-dua',
            ],
            'status' => 'menunggu_validasi',
        ]);

        $response = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$validIncident->id}");

        $response->assertOk();

        // Valid HTTP/HTTPS URLs (including uppercase/mixed-case scheme) must render as clickable links
        $response->assertSee('<a href="HTTPS://portal.jakarta.go.id/halaman-resmi" target="_blank" rel="noopener noreferrer">', false);
        $response->assertSee('<a href="http://example.com/bukti-satu" target="_blank" rel="noopener noreferrer" class="sandbox-evidence-link">', false);
        $response->assertSee('<a href="https://example.com/bukti-dua" target="_blank" rel="noopener noreferrer" class="sandbox-evidence-link">', false);
    }

    public function test_missing_incident_records_return_not_found_response(): void
    {
        $nonExistentId = 999999;

        $responseGet = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$nonExistentId}");
        $responseGet->assertNotFound();

        $responsePatch = $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$nonExistentId}/status", [
                'status' => 'divalidasi',
            ]);
        $responsePatch->assertNotFound();
    }

    public function test_public_showcase_data_remains_unchanged(): void
    {
        $initialCounts = [
            'berita_siber' => DB::table('berita_siber')->count(),
            'events' => DB::table('events')->count(),
            'infografis_keamanan' => DB::table('infografis_keamanan')->count(),
            'peringatan_keamanan' => DB::table('peringatan_keamanan')->count(),
            'peraturan_kebijakan' => DB::table('peraturan_kebijakan')->count(),
            'panduan_teknis' => DB::table('panduan_teknis')->count(),
            'slide_hero' => DB::table('slide_hero')->count(),
            'users' => DB::table('users')->count(),
            'lapor_insiden' => DB::table('lapor_insiden')->count(),
            'lampiran_insiden' => DB::table('lampiran_insiden')->count(),
            'tac_agreements' => DB::table('tac_agreements')->count(),
            'contact_us' => DB::table('contact_us')->count(),
        ];

        // Perform login
        $this->post('/sandbox-admin/login', [
            'username' => $this->adminUsername,
            'password' => $this->adminPassword,
        ]);

        // Create and update sandbox incident
        $incident = $this->createSandboxIncident(['status' => 'menunggu_validasi']);
        $this->withSession(['sandbox_admin_authenticated' => true])
            ->patch("/sandbox-admin/incidents/{$incident->id}/status", [
                'status' => 'divalidasi',
            ]);

        // Perform logout
        $this->withSession(['sandbox_admin_authenticated' => true])
            ->post('/sandbox-admin/logout');

        // Confirm every single showcase SQLite table has identical count
        foreach ($initialCounts as $table => $count) {
            $this->assertEquals($count, DB::table($table)->count(), "Showcase table [{$table}] was mutated!");
        }
    }

    public function test_goatcounter_is_excluded_from_sandbox_admin_routes(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $incident = $this->createSandboxIncident();

        // 1. Login page
        $loginRes = $this->get('/sandbox-admin/login');
        $loginRes->assertOk();
        $loginRes->assertDontSee('gc.zgo.at/count.js');
        $loginRes->assertDontSee('window.goatcounter');
        $loginRes->assertDontSee('footer-privacy-note');

        // 2. Incident list
        $listRes = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get('/sandbox-admin/incidents');
        $listRes->assertOk();
        $listRes->assertDontSee('gc.zgo.at/count.js');
        $listRes->assertDontSee('window.goatcounter');
        $listRes->assertDontSee('footer-privacy-note');

        // 3. Incident detail
        $showRes = $this->withSession(['sandbox_admin_authenticated' => true])
            ->get("/sandbox-admin/incidents/{$incident->id}");
        $showRes->assertOk();
        $showRes->assertDontSee('gc.zgo.at/count.js');
        $showRes->assertDontSee('window.goatcounter');
        $showRes->assertDontSee('footer-privacy-note');
    }
}
