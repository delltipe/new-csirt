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
        $captchaAnswer = session('captcha_answer');
        $this->assertNotNull($captchaAnswer, 'Math CAPTCHA should be generated on form GET');

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
            'captcha_answer' => $captchaAnswer,
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

    public function test_incident_submission_accepts_pentest_payload_strings_without_error(): void
    {
        $this->post('/register', [
            'name' => 'Pentest Researcher',
            'email' => 'pentest@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('bug-hunter.tac'));

        $this->post('/bug-hunter/laporan/agree')->assertRedirect(route('bug-hunter.create'));
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200);
        $captchaAnswer = session('captcha_answer');
        $this->assertNotNull($captchaAnswer);

        // Payload-like strings researchers legitimately paste into reports.
        // Must be accepted (no 500/DB error), stored verbatim, escaped on render.
        $payloadDeskripsi = "<script>alert(1)</script> ' OR '1'='1' -- <img src=x onerror=alert(2)> {{7*7}} \${7*7} ../../etc/passwd ; DROP TABLE lapor_insiden;--";
        $payloadTindakan = 'Blocked <svg onload=alert(3)> & verified "quoted" \'single\'';

        Storage::fake('public');
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'SQL Injection / XSS',
            'waktu_kejadian' => '2026-09-01T14:00',
            'lokasi_url' => 'https://portal.jakarta.go.id/search?q=%3Cscript%3E',
            'down_time' => '01:00',
            'deskripsi' => $payloadDeskripsi,
            'tindakan_teknis' => $payloadTindakan,
            'captcha_answer' => $captchaAnswer,
        ])->assertRedirect(route('bug-hunter.thank-you'));

        $report = IncidentReport::where('kategori_insiden', 'SQL Injection / XSS')->first();
        $this->assertNotNull($report);
        $this->assertSame($payloadDeskripsi, $report->deskripsi);
        $this->assertSame($payloadTindakan, $report->tindakan_teknis);

        // Reporter detail page: payload visible as text, never executed as markup.
        $detail = $this->get('/bug-hunter/laporan/' . $report->id)->assertStatus(200);
        $detail->assertSee(e('<script>alert(1)</script>'), false);
        $detail->assertDontSee('<script>alert(1)</script>', false);
        $detail->assertDontSee('<img src=x onerror=alert(2)>', false);
        $detail->assertDontSee('<svg onload=alert(3)>', false);

        // Dashboard list renders without error too.
        $this->get('/bug-hunter')->assertStatus(200)->assertSee($report->tiket_no);
    }

    public function test_incident_validation_errors_guide_the_user(): void
    {
        $this->post('/register', [
            'name' => 'Validation Tester',
            'email' => 'validation@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('bug-hunter.tac'));

        $this->post('/bug-hunter/laporan/agree')->assertRedirect(route('bug-hunter.create'));
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200);
        $captchaAnswer = session('captcha_answer');
        $this->assertNotNull($captchaAnswer);

        // Invalid category + malformed URL + 4 bukti rows: back with fix guidance, no 500.
        $response = $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Not A Real Category',
            'waktu_kejadian' => '2026-09-01T14:00',
            'lokasi_url' => 'bukan-url',
            'down_time' => '01:00',
            'deskripsi' => 'Deskripsi valid untuk pengujian.',
            'tindakan_teknis' => 'Tindakan valid.',
            'captcha_answer' => $captchaAnswer,
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/1'],
                ['jenis' => 'url', 'url' => 'https://example.com/2'],
                ['jenis' => 'url', 'url' => 'https://example.com/3'],
                ['jenis' => 'url', 'url' => 'https://example.com/4'],
            ],
        ]);
        $response->assertSessionHasErrors(['kategori_insiden', 'lokasi_url', 'bukti']);
        $this->assertDatabaseCount('lapor_insiden', 0);

        // Form re-renders with summary + per-field messages (no exception).
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200);
    }

    public function test_bukti_row_error_summary_links_to_the_exact_control(): void
    {
        $this->post('/register', [
            'name' => 'Bukti Anchor Tester',
            'email' => 'bukti-anchor@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('bug-hunter.tac'));

        $this->post('/bug-hunter/laporan/agree')->assertRedirect(route('bug-hunter.create'));
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200);
        $captchaAnswer = session('captcha_answer');
        $this->assertNotNull($captchaAnswer);

        // Row 1 carries a malformed URL: error key is bukti.1.url.
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing',
            'waktu_kejadian' => '2026-09-01T14:00',
            'lokasi_url' => 'https://portal.jakarta.go.id/halaman/abc',
            'down_time' => '01:00',
            'deskripsi' => 'Deskripsi valid untuk pengujian anchor.',
            'tindakan_teknis' => 'Tindakan valid.',
            'captcha_answer' => $captchaAnswer,
            'bukti' => [
                ['jenis' => 'url', 'url' => 'https://example.com/valid'],
                ['jenis' => 'url', 'url' => 'bukan-url'],
            ],
        ])->assertSessionHasErrors(['bukti.1.url']);
        $this->assertDatabaseCount('lapor_insiden', 0);

        // Summary links to the exact row control. Row inputs themselves are
        // rebuilt client-side from oldBukti, so assert the restore payload
        // carries both rows (same sequential indexes → same control ids).
        // Full anchor-to-control landing still needs a JS browser check.
        $this->get('/bug-hunter/laporan/baru')->assertStatus(200)
            ->assertSee('href="#bukti-1-url"', false)
            ->assertSee('example.com\\/valid', false)
            ->assertSee('bukan-url', false);
    }

    public function test_math_captcha_on_incident_form(): void
    {
        $this->post('/register', [
            'name' => 'Captcha Tester',
            'email' => 'captcha@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('bug-hunter.tac'));

        $this->post('/bug-hunter/laporan/agree')->assertRedirect(route('bug-hunter.create'));

        $this->get('/bug-hunter/laporan/baru')->assertStatus(200)->assertSee('Verifikasi');
        $correct = session('captcha_answer');
        $this->assertNotNull($correct);
        $question = session('captcha_q');
        $this->assertMatchesRegularExpression('/^\d+ [\+\-] \d+ = \?$/', $question);

        // Wrong answer rejected
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing',
            'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://portal.jakarta.go.id/halaman/abc',
            'down_time' => '02:15',
            'deskripsi' => 'Test captcha wrong.',
            'tindakan_teknis' => 'Test.',
            'captcha_answer' => $correct + 99,
        ])->assertSessionHasErrors('captcha_answer');

        // Missing answer rejected
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing',
            'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://portal.jakarta.go.id/halaman/abc',
            'down_time' => '02:15',
            'deskripsi' => 'Test missing.',
            'tindakan_teknis' => 'Test.',
        ])->assertSessionHasErrors('captcha_answer');

        // Refresh generates new question
        $oldQuestion = session('captcha_q');
        $this->get(route('captcha.refresh'))->assertOk()->assertJsonStructure(['question']);
        $newQuestion = session('captcha_q');
        $this->assertNotEquals($oldQuestion, $newQuestion);
        $this->assertMatchesRegularExpression('/^\d+ [\+\-] \d+ = \?$/', $newQuestion);
        $newAnswer = session('captcha_answer');
        $this->assertNotNull($newAnswer);

        // Correct answer after refresh succeeds (minimal valid report)
        Storage::fake('public');
        $this->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing',
            'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://portal.jakarta.go.id/halaman/abc',
            'down_time' => '02:15',
            'deskripsi' => 'Test captcha correct after refresh.',
            'tindakan_teknis' => 'Test correct.',
            'captcha_answer' => $newAnswer,
        ])->assertRedirect(route('bug-hunter.thank-you'));
    }

    public function test_math_captcha_on_contact_form(): void
    {
        $this->get('/contact')->assertStatus(200)->assertSee('Verifikasi');
        $correct = session('captcha_answer');
        $this->assertNotNull($correct);

        // Wrong answer
        $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi2@example.com',
            'subject' => 'Test',
            'message' => 'Halo',
            'inquiry_type' => 'general',
            'captcha_answer' => $correct + 50,
        ])->assertSessionHasErrors('captcha_answer');

        // Correct answer
        $fresh = session('captcha_answer'); // after wrong, regenerated
        $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi2@example.com',
            'subject' => 'Test OK',
            'message' => 'Halo CSIRT',
            'inquiry_type' => 'general',
            'captcha_answer' => $fresh,
        ])->assertRedirect(route('contact.thank-you'));
        $this->assertDatabaseHas('contact_us', ['email' => 'budi2@example.com', 'subject' => 'Test OK']);
    }
}
