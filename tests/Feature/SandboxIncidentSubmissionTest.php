<?php

namespace Tests\Feature;

use App\Models\SandboxIncident;
use App\Support\MathCaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SandboxIncidentSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);

        // Ensure the sandbox migration is run on the sandbox connection
        if (!Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->up();
        }
    }

    protected function tearDown(): void
    {
        if (Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->down();
        }

        parent::tearDown();
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'kategori_insiden' => 'Website Defacement',
            'waktu_kejadian' => '2026-10-24T12:00',
            'down_time' => '00:00',
            'lokasi_url' => 'https://contoh-demo.jakarta.go.id/halaman-uji',
            'deskripsi' => 'Laporan pengujian simulasi defacement pada situs pratinjau publik dengan data fiktif.',
            'tindakan_teknis' => 'Mitigasi simulasi: memulihkan cadangan data statis dan memperbarui konfigurasi server.',
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/demo-bukti-1'],
                ['jenis' => 'url', 'url' => 'https://example.com/demo-bukti-2'],
            ],
            'captcha_answer' => '15',
            'syarat_ketentuan' => '1',
        ], $overrides);
    }

    public function test_global_preview_warning_banner_is_present_on_relevant_pages(): void
    {
        $pages = ['/', '/bug-hunter/laporan/baru', '/contact', '/news', '/events'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertOk();
            $response->assertSee('preview-global-banner');
            $response->assertSee('MODE SIMULASI &amp; PRATINJAU PUBLIK', false);
            $response->assertSee('Wajib gunakan informasi fiktif', false);
        }
    }

    public function test_form_warning_and_fictional_stepper_are_present_on_incident_report_page(): void
    {
        $response = $this->get('/bug-hunter/laporan/baru');
        $response->assertOk();

        // Form header and alert
        $response->assertSee('Simulasi Pratinjau Publik');
        $response->assertSee('Peringatan Keamanan &amp; Data Fiktif Lingkungan Pengujian', false);
        $response->assertSee('Dilarang keras memasukkan data pribadi asli', false);

        // Stepper labels
        $response->assertSee('1. Panduan &amp; Data Fiktif', false);
        $response->assertSee('2. Pengisian Detail Insiden', false);
        $response->assertSee('3. Tiket Simulasi', false);

        // Evidence section & agreement checkbox
        $response->assertSee('Bukti Tautan Simulasi');
        $response->assertSee('Unggah file biner dinonaktifkan demi keamanan pratinjau', false);
        $response->assertSee('field-syarat-ketentuan');
        $response->assertSee('Pernyataan Data Fiktif &amp; Simulasi', false);

        // Cancel button points to home
        $response->assertSee(route('home'));
    }

    public function test_valid_fictional_demo_incident_submission_succeeds_and_creates_sandbox_record(): void
    {
        $initialSandboxCount = SandboxIncident::count();
        $this->assertEquals(0, $initialSandboxCount);

        $payload = $this->validPayload();
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $payload);

        $response->assertRedirect('/bug-hunter/laporan/selesai');
        $response->assertSessionHas('tiket_no');

        $tiketNo = session('tiket_no');
        $this->assertMatchesRegularExpression('/^DEMO-\d{4}-[A-Z0-9]{8}$/', $tiketNo);

        // Verify sandbox record
        $this->assertEquals(1, SandboxIncident::count());
        $incident = SandboxIncident::first();
        $this->assertEquals($tiketNo, $incident->tiket_no);
        $this->assertEquals('Website Defacement', $incident->kategori_insiden);
        $this->assertEquals('https://contoh-demo.jakarta.go.id/halaman-uji', $incident->lokasi_url);
        $this->assertEquals('00:00', $incident->down_time);
        $this->assertEquals('menunggu_validasi', $incident->status);
        $this->assertIsArray($incident->bukti_urls);
        $this->assertCount(2, $incident->bukti_urls);
        $this->assertEquals('https://example.com/demo-bukti-1', $incident->bukti_urls[0]);

        // Verify showcase database remains unmutated
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
        $this->assertEquals(0, DB::table('users')->count());
    }

    public function test_ticket_number_format_matches_demo_year_alphanumeric(): void
    {
        $payload = $this->validPayload();
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $payload);

        $response->assertRedirect('/bug-hunter/laporan/selesai');
        $tiketNo = session('tiket_no');
        $currentYear = date('Y');

        $this->assertNotNull($tiketNo);
        $this->assertStringStartsWith("DEMO-{$currentYear}-", $tiketNo);
        $this->assertEquals(18, strlen($tiketNo)); // DEMO-YYYY-XXXXXXXX = 5 + 4 + 1 + 8 = 18
    }

    public function test_invalid_inputs_are_rejected_with_validation_errors(): void
    {
        // 1. Missing required fields
        $response = $this->from('/bug-hunter/laporan/baru')->post('/bug-hunter/laporan/simpan', []);
        $response->assertRedirect('/bug-hunter/laporan/baru');
        $response->assertSessionHasErrors([
            'kategori_insiden',
            'waktu_kejadian',
            'down_time',
            'lokasi_url',
            'deskripsi',
            'tindakan_teknis',
            'captcha_answer',
            'syarat_ketentuan',
        ]);

        // 2. Text exceeds max limit (> 5000 characters)
        $overlimitPayload = $this->validPayload([
            'deskripsi' => str_repeat('A', 5001),
        ]);
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $overlimitPayload);
        $response->assertSessionHasErrors('deskripsi');

        // 3. Invalid URL format
        $badUrlPayload = $this->validPayload([
            'lokasi_url' => 'not-a-valid-url',
        ]);
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $badUrlPayload);
        $response->assertSessionHasErrors('lokasi_url');

        // 4. Missing terms agreement checkbox
        $noTermsPayload = $this->validPayload([
            'syarat_ketentuan' => null,
        ]);
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $noTermsPayload);
        $response->assertSessionHasErrors('syarat_ketentuan');

        // 5. Wrong CAPTCHA answer
        $badCaptchaPayload = $this->validPayload([
            'captcha_answer' => '999',
        ]);
        $response = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $badCaptchaPayload);
        $response->assertSessionHasErrors('captcha_answer');

        // Confirm zero records created in sandbox
        $this->assertEquals(0, SandboxIncident::count());
    }

    public function test_binary_file_uploads_are_rejected_and_no_files_stored_on_disk(): void
    {
        Storage::fake('public');

        $fakeFile = UploadedFile::fake()->create('malicious_exploit.exe', 500, 'application/octet-stream');

        // Attempt submission with file in bukti array
        $payloadWithFile = $this->validPayload([
            'bukti' => [
                ['jenis' => 'file', 'file' => $fakeFile],
            ],
            'file' => $fakeFile,
        ]);

        $response = $this->from('/bug-hunter/laporan/baru')->post('/bug-hunter/laporan/simpan', $payloadWithFile);
        $response->assertRedirect('/bug-hunter/laporan/baru');
        $response->assertSessionHasErrors('bukti');

        // Assert no storage files created
        $this->assertEmpty(Storage::disk('public')->allFiles());

        // Assert no database records created
        $this->assertEquals(0, SandboxIncident::count());
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
    }

    public function test_evidence_allows_maximum_three_urls(): void
    {
        $payloadWith4Urls = $this->validPayload([
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/1'],
                ['jenis' => 'url', 'url' => 'https://example.com/2'],
                ['jenis' => 'url', 'url' => 'https://example.com/3'],
                ['jenis' => 'url', 'url' => 'https://example.com/4'],
            ],
        ]);

        $response = $this->from('/bug-hunter/laporan/baru')->post('/bug-hunter/laporan/simpan', $payloadWith4Urls);
        $response->assertSessionHasErrors('bukti');
        $this->assertEquals(0, SandboxIncident::count());
    }

    public function test_url_validation_accepts_http_and_https_schemes(): void
    {
        // 1. Valid HTTP scheme
        $httpPayload = $this->validPayload([
            'lokasi_url' => 'http://contoh-demo.jakarta.go.id/halaman-uji-http',
            'bukti' => [
                ['jenis' => 'url', 'url' => 'http://example.com/demo-bukti-http'],
            ],
        ]);
        $responseHttp = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $httpPayload);
        $responseHttp->assertRedirect('/bug-hunter/laporan/selesai');
        $responseHttp->assertSessionHasNoErrors();

        // 2. Valid HTTPS scheme
        $httpsPayload = $this->validPayload([
            'lokasi_url' => 'https://contoh-demo.jakarta.go.id/halaman-uji-https',
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/demo-bukti-https'],
            ],
        ]);
        $responseHttps = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $httpsPayload);
        $responseHttps->assertRedirect('/bug-hunter/laporan/selesai');
        $responseHttps->assertSessionHasNoErrors();
    }

    public function test_url_validation_rejects_ftp_and_disallowed_schemes(): void
    {
        // 1. FTP scheme for incident location URL
        $ftpLocationPayload = $this->validPayload([
            'lokasi_url' => 'ftp://contoh-demo.jakarta.go.id/halaman-ftp',
        ]);
        $responseFtpLoc = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $ftpLocationPayload);
        $responseFtpLoc->assertRedirect('/bug-hunter/laporan/baru');
        $responseFtpLoc->assertSessionHasErrors('lokasi_url');

        // 2. FTP scheme for evidence URL
        $ftpEvidencePayload = $this->validPayload([
            'bukti' => [
                ['jenis' => 'url', 'url' => 'ftp://example.com/demo-bukti-ftp'],
            ],
        ]);
        $responseFtpEv = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $ftpEvidencePayload);
        $responseFtpEv->assertRedirect('/bug-hunter/laporan/baru');
        $responseFtpEv->assertSessionHasErrors('bukti.0.url');

        // 3. Disallowed pseudo-schemes (javascript:, file:)
        $badSchemesPayload = $this->validPayload([
            'lokasi_url' => 'javascript:alert(1)',
            'bukti' => [
                ['jenis' => 'url', 'url' => 'file:///etc/passwd'],
            ],
        ]);
        $responseBad = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $badSchemesPayload);
        $responseBad->assertRedirect('/bug-hunter/laporan/baru');
        $responseBad->assertSessionHasErrors(['lokasi_url', 'bukti.0.url']);

        // 4. Overlong location URL (> 255 chars)
        $overlongLocPayload = $this->validPayload([
            'lokasi_url' => 'https://example.com/' . str_repeat('a', 250),
        ]);
        $responseOverlongLoc = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $overlongLocPayload);
        $responseOverlongLoc->assertSessionHasErrors('lokasi_url');

        // 5. Overlong evidence URL (> 255 chars)
        $overlongEvPayload = $this->validPayload([
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/' . str_repeat('b', 250)],
            ],
        ]);
        $responseOverlongEv = $this->from('/bug-hunter/laporan/baru')
            ->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $overlongEvPayload);
        $responseOverlongEv->assertSessionHasErrors('bukti.0.url');
    }

    public function test_rate_limiting_is_enforced_on_demo_incident_submission(): void
    {
        // 5 attempts allowed within 10 minutes
        for ($i = 0; $i < 5; $i++) {
            $payload = $this->validPayload([
                'lokasi_url' => "https://contoh-demo.jakarta.go.id/uji-{$i}",
            ]);
            $response = $this->withSession([MathCaptcha::SESSION_ANSWER => 15])
                ->post('/bug-hunter/laporan/simpan', $payload);
            $response->assertRedirect('/bug-hunter/laporan/selesai');
        }

        $this->assertEquals(5, SandboxIncident::count());

        // 6th attempt should be rate limited (429)
        $payload6 = $this->validPayload([
            'lokasi_url' => 'https://contoh-demo.jakarta.go.id/uji-6',
        ]);
        $response6 = $this->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $payload6);
        $response6->assertStatus(429);

        // Count remains 5
        $this->assertEquals(5, SandboxIncident::count());
    }

    public function test_confirmation_receipt_displays_ticket_number_and_sandbox_disclaimer(): void
    {
        $demoTicket = 'DEMO-2026-TEST9999';

        $response = $this->withSession(['tiket_no' => $demoTicket])->get('/bug-hunter/laporan/selesai');
        $response->assertOk();

        // Receipt verification
        $response->assertSee($demoTicket);
        $response->assertSee('No Tiket Simulasi Demo');
        $response->assertSee('Pemberitahuan Pratinjau Publik (Sandbox)', false);
        $response->assertSee('Nomor tiket ini merupakan referensi simulasi pengujian', false);
        $response->assertSee('tidak</strong> diteruskan ke tim operasional kedaruratan riil', false);

        // Navigation check
        $response->assertSee(route('home'));
        $response->assertSee(route('bug-hunter.create'));
        $response->assertDontSee('/bug-hunter/laporan/1');

        // Ensure no internal DB errors or stack traces are leaked
        $response->assertDontSee('SQLSTATE');
        $response->assertDontSee('Exception');
        $response->assertDontSee('Call to undefined');
    }

    public function test_original_showcase_tables_remain_completely_unmutated(): void
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

        // Perform valid submission
        $payload = $this->validPayload();
        $this->withSession([MathCaptcha::SESSION_ANSWER => 15])
            ->post('/bug-hunter/laporan/simpan', $payload);

        // Verify every showcase table has identical row count
        foreach ($initialCounts as $table => $count) {
            $this->assertEquals($count, DB::table($table)->count(), "Table [$table] was unexpectedly mutated!");
        }
    }

    public function test_safe_contact_form_demo_remains_client_side_and_backend_post_rejected(): void
    {
        $initialContactCount = DB::table('contact_us')->count();
        $this->assertEquals(0, $initialContactCount);

        $response = $this->from('/contact')->post('/contact', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'pesan' => 'Trying to submit message',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors('pesan');
        $this->assertEquals(0, DB::table('contact_us')->count());
    }

    public function test_unrelated_routes_remain_strictly_blocked(): void
    {
        // Protected detail route remains forbidden
        $this->get('/bug-hunter/laporan/1')->assertForbidden();

        // Mutating public auth strictly forbidden
        $this->post('/login', ['email' => 'user@example.com', 'password' => 'secret'])->assertForbidden();
        $this->post('/register', ['name' => 'User', 'email' => 'user@example.com', 'password' => 'secret'])->assertForbidden();

        // Admin routes strictly 404
        $this->get('/admin')->assertNotFound();
        $this->get('/admin/login')->assertNotFound();
        $this->post('/admin/login', ['email' => 'admin@gmail.com', 'password' => '12345678'])->assertNotFound();
        $this->get('/admin/incidents')->assertNotFound();
        $this->get('/admin/dashboard')->assertNotFound();
    }

    public function test_no_network_connection_to_live_postgresql_is_attempted(): void
    {
        // Sandbox driver must be sqlite in test environment
        $this->assertEquals('sqlite', config('database.connections.sandbox.driver'));
        $this->assertEquals(':memory:', config('database.connections.sandbox.database'));
    }
}
