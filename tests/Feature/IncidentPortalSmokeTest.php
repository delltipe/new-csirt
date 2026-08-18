<?php

namespace Tests\Feature;

use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IncidentPortalSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_auth_and_full_report_flow(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/bug-hunter')->assertRedirect(route('login'));

        $this->post('/register', [
            'name' => 'Budi Pelapor',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('bug-hunter.tac'));

        $this->assertDatabaseHas('users', ['email' => 'budi@example.com', 'is_bug_hunter' => true]);

        $this->get('/bug-hunter/laporan')->assertStatus(200);
        $this->post('/bug-hunter/laporan/agree')->assertRedirect(route('bug-hunter.create'));
        $this->assertDatabaseHas('tac_agreements', ['version' => '2026.08']);

        // Agreed users skip TaC straight to the form
        $this->get('/bug-hunter/laporan')->assertRedirect(route('bug-hunter.create'));
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200);

        Storage::fake('public');
        $png = UploadedFile::fake()->createWithContent(
            'bukti.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==')
        );
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing',
            'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://portal.jakarta.go.id/halaman/abc',
            'down_time' => '02:15',
            'deskripsi' => 'Ditemukan halaman phishing meniru portal.',
            'tindakan_teknis' => 'Laporkan ke CSIRT dan blokir domain.',
            'bukti' => [
                ['jenis' => 'file', 'file' => $png, 'url' => ''],
                ['jenis' => 'url', 'file' => null, 'url' => 'https://example.com/repro'],
                ['jenis' => '', 'file' => null, 'url' => ''],
            ],
        ])->assertRedirect(route('bug-hunter.thank-you'));

        $this->assertDatabaseHas('lapor_insiden', [
            'kategori_insiden' => 'Phishing',
            'status' => IncidentReport::STATUS_PENDING,
        ]);

        $report = IncidentReport::first();
        $this->assertNotNull($report);
        $this->assertMatchesRegularExpression('/^INS-2026-\d{4}$/', $report->tiket_no);
        $this->assertSame(2, $report->attachments()->count());

        $this->get('/bug-hunter')->assertStatus(200)->assertSee($report->tiket_no);
        $this->get('/bug-hunter/laporan/' . $report->id)->assertStatus(200);
    }

    public function test_admin_review_flow(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password', 'is_admin' => true]);
        $reporter = User::create(['name' => 'Reporter', 'email' => 'rep@example.com', 'password' => 'password', 'is_bug_hunter' => true]);

        $report = IncidentReport::create([
            'user_id' => $reporter->id,
            'tiket_no' => 'INS-2026-0001',
            'kategori_insiden' => 'Malware',
            'lokasi_url' => 'https://portal.jakarta.go.id/x',
            'deskripsi' => 'Temuan malware.',
            'tindakan_teknis' => 'Isolasi.',
            'status' => IncidentReport::STATUS_PENDING,
        ]);

        $this->actingAs($admin);
        $this->get('/admin')->assertStatus(200)->assertSee('Insiden');
        $this->get('/admin/incidents')->assertStatus(200)->assertSee($report->tiket_no);
        $this->get('/admin/incidents/' . $report->id)->assertStatus(200);

        // Invalid transition rejected
        $this->post('/admin/incidents/' . $report->id . '/review', ['status' => IncidentReport::STATUS_DONE])
            ->assertSessionHasErrors('status');

        // Valid transition + CWE/severity
        $this->post('/admin/incidents/' . $report->id . '/review', [
            'cwe' => 'CWE-79', 'severity' => 'High', 'status' => IncidentReport::STATUS_VALIDATED,
        ])->assertSessionHasNoErrors();

        $report->refresh();
        $this->assertSame(IncidentReport::STATUS_VALIDATED, $report->status);
        $this->assertSame('CWE-79', $report->cwe);
        $this->assertSame('High', $report->severity);

        // Reporter sees updated CWE/severity/status
        $this->actingAs($reporter);
        $this->get('/bug-hunter')->assertSee('CWE-79')->assertSee('High');
        $this->get('/bug-hunter/laporan/' . $report->id)->assertSee(IncidentReport::labels()[IncidentReport::STATUS_VALIDATED]);
    }

    public function test_admin_soft_deletes_incident_report(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin2@example.com', 'password' => 'password', 'is_admin' => true]);
        $reporter = User::create(['name' => 'Reporter', 'email' => 'rep2@example.com', 'password' => 'password', 'is_bug_hunter' => true]);

        $report = IncidentReport::create([
            'user_id' => $reporter->id,
            'tiket_no' => 'INS-2026-0002',
            'kategori_insiden' => 'Phishing',
            'lokasi_url' => 'https://portal.jakarta.go.id/y',
            'deskripsi' => 'Laporan untuk dihapus.',
            'tindakan_teknis' => 'Pantau.',
            'status' => IncidentReport::STATUS_PENDING,
        ]);

        $this->actingAs($admin);
        $this->get('/admin/incidents')->assertOk()->assertSee($report->tiket_no);

        $this->post('/admin/incidents/' . $report->id . '/delete')
            ->assertRedirect(route('admin.incidents.list'));

        $this->assertSoftDeleted('lapor_insiden', ['id' => $report->id]);

        // Soft-deleted reports leave the list and 404 on detail/review
        $this->get('/admin/incidents')->assertOk()->assertDontSee($report->tiket_no);
        $this->get('/admin/incidents/' . $report->id)->assertNotFound();

        // Admin restores it, and the full path works again
        $report->restore();
        $this->get('/admin/incidents')->assertOk()->assertSee($report->tiket_no);
    }
}
