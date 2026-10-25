<?php

namespace Tests\Feature;

use App\Models\SandboxIncident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IncidentTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);

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

    protected function createSampleSandboxIncident(array $overrides = []): SandboxIncident
    {
        return SandboxIncident::create(array_merge([
            'tiket_no' => 'DEMO-2026-TEST0001',
            'kategori_insiden' => 'Website Defacement',
            'waktu_kejadian' => now()->subDay(),
            'lokasi_url' => 'https://demo-subdomain.jakarta.go.id/target',
            'down_time' => '01:30',
            'deskripsi' => 'SECRET_PAYLOAD: Tampilan halaman index diubah menjadi deface hacker.',
            'tindakan_teknis' => 'SECRET_MITIGATION: Restore backup database dan perbaiki permission.',
            'bukti_urls' => ['https://secret-evidence.com/proof.png'],
            'status' => SandboxIncident::STATUS_IN_PROGRESS,
        ], $overrides));
    }

    public function test_public_tracking_page_renders_successfully(): void
    {
        $response = $this->get(route('ticket.track'));

        $response->assertStatus(200);
        $response->assertSee('Lacak Status Penanganan');
        $response->assertSee('Pencarian Nomor Tiket');
        $response->assertSee('DEMO-2026-B7TGZTCI');
    }

    public function test_public_tracking_with_valid_ticket_number(): void
    {
        $this->createSampleSandboxIncident();

        $response = $this->get(route('ticket.track', ['tiket' => 'DEMO-2026-TEST0001']));

        $response->assertStatus(200);
        $response->assertSee('DEMO-2026-TEST0001');
        $response->assertSee('Ditindaklanjuti');
        $response->assertSee('Website Defacement');
        $response->assertSee('Sandbox Pengujian Publik');
    }

    public function test_public_tracking_case_insensitive_lookup(): void
    {
        $this->createSampleSandboxIncident();

        $response = $this->get(route('ticket.track', ['tiket' => 'demo-2026-test0001']));

        $response->assertStatus(200);
        $response->assertSee('DEMO-2026-TEST0001');
        $response->assertSee('Ditindaklanjuti');
    }

    public function test_public_tracking_with_nonexistent_ticket_number(): void
    {
        $response = $this->get(route('ticket.track', ['tiket' => 'DEMO-2026-99999999']));

        $response->assertStatus(200);
        $response->assertSee('tidak ditemukan dalam basis data sandbox');
        $response->assertDontSee('Nomor Tiket Demo');
    }

    public function test_public_tracking_with_invalid_format_ticket(): void
    {
        $response = $this->get(route('ticket.track', ['tiket' => 'INVALID-FORMAT-123']));

        $response->assertStatus(200);
        $response->assertSee('Format nomor tiket simulasi tidak sesuai');
    }

    public function test_public_tracking_does_not_leak_sensitive_details(): void
    {
        $this->createSampleSandboxIncident();

        $response = $this->get(route('ticket.track', ['tiket' => 'DEMO-2026-TEST0001']));

        $response->assertStatus(200);
        $response->assertDontSee('SECRET_PAYLOAD');
        $response->assertDontSee('SECRET_MITIGATION');
        $response->assertDontSee('https://secret-evidence.com/proof.png');
    }

    public function test_public_tracking_with_rejected_status(): void
    {
        $this->createSampleSandboxIncident([
            'tiket_no' => 'DEMO-2026-TEST0002',
            'status' => SandboxIncident::STATUS_REJECTED,
        ]);

        $response = $this->get(route('ticket.track', ['tiket' => 'DEMO-2026-TEST0002']));

        $response->assertStatus(200);
        $response->assertSee('Ditolak');
        $response->assertSee('Laporan simulasi ini dinyatakan tidak valid setelah verifikasi teknis');
    }

    public function test_public_tracking_status_labels_and_lifecycle_stepper_mapping(): void
    {
        // 1. Pending (menunggu_validasi)
        $pending = $this->createSampleSandboxIncident([
            'tiket_no' => 'DEMO-2026-STAT0001',
            'status' => SandboxIncident::STATUS_PENDING,
        ]);
        $resPending = $this->get(route('ticket.track', ['tiket' => $pending->tiket_no]));
        $resPending->assertOk();
        $resPending->assertSee('Menunggu Validasi');
        $resPending->assertSee('aria-current="step"', false);

        // 2. Validated (divalidasi)
        $validated = $this->createSampleSandboxIncident([
            'tiket_no' => 'DEMO-2026-STAT0002',
            'status' => SandboxIncident::STATUS_VALIDATED,
        ]);
        $resValidated = $this->get(route('ticket.track', ['tiket' => $validated->tiket_no]));
        $resValidated->assertOk();
        $resValidated->assertSee('Divalidasi');

        // 3. Recovered (dipulihkan)
        $recovered = $this->createSampleSandboxIncident([
            'tiket_no' => 'DEMO-2026-STAT0003',
            'status' => SandboxIncident::STATUS_RECOVERED,
        ]);
        $resRecovered = $this->get(route('ticket.track', ['tiket' => $recovered->tiket_no]));
        $resRecovered->assertOk();
        $resRecovered->assertSee('Dipulihkan');

        // 4. Completed (selesai)
        $done = $this->createSampleSandboxIncident([
            'tiket_no' => 'DEMO-2026-STAT0004',
            'status' => SandboxIncident::STATUS_DONE,
        ]);
        $resDone = $this->get(route('ticket.track', ['tiket' => $done->tiket_no]));
        $resDone->assertOk();
        $resDone->assertSee('Selesai');
    }

    public function test_public_tracking_rate_limiting(): void
    {
        // Route has throttle:60,1
        $response = $this->get(route('ticket.track'));
        $response->assertOk();
        $this->assertTrue($response->headers->has('X-RateLimit-Limit') || $response->headers->has('x-ratelimit-limit'));
    }

    public function test_public_tracking_strictly_queries_sandbox_and_leaves_showcase_tables_unmutated(): void
    {
        $this->createSampleSandboxIncident();

        $response = $this->get(route('ticket.track', ['tiket' => 'DEMO-2026-TEST0001']));
        $response->assertStatus(200);

        $this->assertEquals(0, DB::table('users')->count());
        $this->assertEquals(0, DB::table('lapor_insiden')->count());
        $this->assertEquals(0, DB::table('lampiran_insiden')->count());
        $this->assertEquals(0, DB::table('tac_agreements')->count());
    }
}
