<?php

namespace Tests\Feature;

use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function createSampleReport(array $overrides = []): IncidentReport
    {
        $user = User::factory()->create([
            'email' => 'reporter.private@example.com',
            'is_bug_hunter' => true,
        ]);

        return IncidentReport::create(array_merge([
            'user_id' => $user->id,
            'tiket_no' => 'INS-2026-0001',
            'kategori_insiden' => 'Website Defacement',
            'waktu_kejadian' => now()->subDay(),
            'lokasi_url' => 'https://subdomain.jakarta.go.id/target',
            'down_time' => '01:30',
            'deskripsi' => 'SECRET_PAYLOAD: Tampilan halaman index diubah menjadi deface hacker.',
            'tindakan_teknis' => 'SECRET_MITIGATION: Restore backup database dan perbaiki permission.',
            'cwe' => 'CWE-79',
            'severity' => 'medium',
            'status' => IncidentReport::STATUS_IN_PROGRESS,
        ], $overrides));
    }

    public function test_public_tracking_page_renders_successfully(): void
    {
        $response = $this->get(route('ticket.track'));

        $response->assertStatus(200);
        $response->assertSee('Lacak Status Penanganan');
        $response->assertSee('Pencarian Nomor Tiket');
    }

    public function test_public_tracking_with_valid_ticket_number(): void
    {
        $report = $this->createSampleReport();

        $response = $this->get(route('ticket.track', ['tiket' => 'INS-2026-0001']));

        $response->assertStatus(200);
        $response->assertSee('INS-2026-0001');
        $response->assertSee('Ditindaklanjuti');
        $response->assertSee('Website Defacement');
        $response->assertSee('CWE-79');
        $response->assertSee('MEDIUM');
    }

    public function test_public_tracking_case_insensitive_lookup(): void
    {
        $this->createSampleReport();

        $response = $this->get(route('ticket.track', ['tiket' => 'ins-2026-0001']));

        $response->assertStatus(200);
        $response->assertSee('INS-2026-0001');
        $response->assertSee('Ditindaklanjuti');
    }

    public function test_public_tracking_with_nonexistent_ticket_number(): void
    {
        $response = $this->get(route('ticket.track', ['tiket' => 'INS-2026-9999']));

        $response->assertStatus(200);
        $response->assertSee('tidak ditemukan dalam sistem JakartaProv-CSIRT');
        $response->assertDontSee('Nomor Tiket Resmi');
    }

    public function test_public_tracking_with_invalid_format_ticket(): void
    {
        $response = $this->get(route('ticket.track', ['tiket' => 'INVALID-FORMAT-123']));

        $response->assertStatus(200);
        $response->assertSee('Format nomor tiket tidak sesuai');
    }

    public function test_public_tracking_does_not_leak_sensitive_details(): void
    {
        $this->createSampleReport();

        $response = $this->get(route('ticket.track', ['tiket' => 'INS-2026-0001']));

        $response->assertStatus(200);
        $response->assertDontSee('SECRET_PAYLOAD');
        $response->assertDontSee('SECRET_MITIGATION');
        $response->assertDontSee('reporter.private@example.com');
    }

    public function test_public_tracking_with_rejected_status(): void
    {
        $this->createSampleReport([
            'tiket_no' => 'INS-2026-0002',
            'status' => IncidentReport::STATUS_REJECTED,
        ]);

        $response = $this->get(route('ticket.track', ['tiket' => 'INS-2026-0002']));

        $response->assertStatus(200);
        $response->assertSee('Ditolak');
        $response->assertSee('Laporan ini dinyatakan tidak valid setelah verifikasi teknis');
    }

    public function test_public_tracking_excludes_soft_deleted_ticket(): void
    {
        $report = $this->createSampleReport([
            'tiket_no' => 'INS-2026-0003',
        ]);
        $report->delete(); // soft delete

        $response = $this->get(route('ticket.track', ['tiket' => 'INS-2026-0003']));

        $response->assertStatus(200);
        $response->assertSee('tidak ditemukan dalam sistem JakartaProv-CSIRT');
    }
}
