<?php

namespace Tests\Feature;

use App\Models\IncidentReport;
use App\Models\LampiranInsiden;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthenticationEvidenceBoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_evidence_access_and_soft_deletion(): void
    {
        Storage::fake('evidence');
        Storage::fake('public');
        $owner = User::factory()->create(['is_bug_hunter' => true]);
        $other = User::factory()->create(['is_bug_hunter' => true]);
        $admin = User::factory()->create(['is_admin' => true]);
        $report = IncidentReport::create(['user_id' => $owner->id, 'tiket_no' => 'INS-2026-0001', 'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'test']);
        $attachment = LampiranInsiden::create(['laporan_id' => $report->id, 'jenis' => 'file', 'value' => 'bukti_laporan/test.pdf']);
        Storage::disk('evidence')->put($attachment->value, 'private proof');
        $url = route('evidence.download', $attachment);
        $this->get($url)->assertRedirect(route('login'));
        $this->actingAs($other)->get($url)->assertNotFound()->assertSee('Lampiran tidak tersedia')->assertSee('Kembali ke Beranda');
        $this->actingAs($owner)->get($url)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private');
        $this->actingAs($admin)->get($url)->assertOk();
        $report->delete();
        $this->actingAs($owner)->get($url)->assertNotFound();
        $this->actingAs($admin)->get($url)->assertNotFound();
        Storage::disk('evidence')->assertExists($attachment->value);
    }

    public function test_legacy_and_missing_files_and_invalid_paths(): void
    {
        Storage::fake('evidence');
        Storage::fake('public');
        $owner = User::factory()->create(['is_bug_hunter' => true]);
        $report = IncidentReport::create(['user_id' => $owner->id, 'tiket_no' => 'INS-2026-0002', 'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'test']);
        $attachment = LampiranInsiden::create(['laporan_id' => $report->id, 'jenis' => 'file', 'value' => 'bukti_laporan/legacy.pdf']);
        Storage::disk('public')->put($attachment->value, 'legacy');
        $this->actingAs($owner)->get(route('evidence.download', $attachment))->assertOk();
        $attachment->update(['value' => 'bukti_laporan/missing.pdf']);
        $this->get(route('evidence.download', $attachment))->assertNotFound();
        $attachment->update(['value' => '../legacy.pdf']);
        $this->get(route('evidence.download', $attachment))->assertNotFound();
        Storage::disk('public')->assertExists('bukti_laporan/legacy.pdf');
    }

    public function test_admin_login_rejects_reporter_and_logout_invalidates_session(): void
    {
        $reporter = User::factory()->create(['is_bug_hunter' => true, 'password' => bcrypt('password')]);
        $this->post('/admin/login', ['email' => $reporter->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $admin = User::factory()->create(['is_admin' => true, 'password' => bcrypt('password')]);
        $this->withSession(['marker' => 'old']);
        $before = session()->getId();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
        $this->assertNotSame($before, session()->getId());
        $token = session()->token();
        $id = session()->getId();
        $this->post('/admin/logout')->assertRedirect(route('admin.login'));
        $this->assertGuest();
        $this->assertNotSame($id, session()->getId());
        $this->assertNotSame($token, session()->token());
        $this->assertNull(session('marker'));
        $this->actingAs($reporter)->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_login_limit_is_shared_across_endpoints_and_expires(): void
    {
        $this->post('/login', ['email' => ['invalid'], 'password' => 'bad'])->assertSessionHasErrors('email');
        for ($i = 0; $i < 5; $i++) {
            $this->post($i % 2 ? '/admin/login' : '/login', ['email' => 'test@example.com', 'password' => 'bad'])->assertStatus(302);
        }
        $this->post('/login', ['email' => 'test@example.com', 'password' => 'bad'])->assertStatus(429)->assertSee('Terlalu banyak permintaan')->assertSee('Kembali ke Beranda')->assertHeader('Retry-After');
        $this->travel(61)->seconds();
        $this->post('/admin/login', ['email' => 'test@example.com', 'password' => 'bad'])->assertStatus(302);
    }

    public function test_registration_and_tracking_limits(): void
    {
        for ($i = 0; $i < 5; $i++) $this->post('/register', [])->assertStatus(302);
        $this->post('/register', [])->assertStatus(429);
        for ($i = 0; $i < 30; $i++) $this->get('/lacak-laporan')->assertOk();
        $this->get('/lacak-laporan')->assertStatus(429);
        $this->travel(61)->seconds();
        $this->get('/lacak-laporan')->assertOk();
        $this->post('/register', [])->assertStatus(302);
    }

    public function test_public_login_remember_and_logout_session(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'password' => bcrypt('password')]);
        $this->withSession(['marker' => 'old']);
        $id = session()->getId();
        $this->post('/login', ['email' => $admin->email, 'password' => 'password', 'remember' => '1'])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->assertNotSame($id, session()->getId());
        $this->assertNotNull($admin->fresh()->getRememberToken());
        $token = session()->token();
        $id = session()->getId();
        $this->post('/logout')->assertRedirect(route('home'));
        $this->assertGuest();
        $this->assertNotSame($id, session()->getId());
        $this->assertNotSame($token, session()->token());
        $this->assertNull(session('marker'));
    }

    public function test_admin_can_download_a_copied_evidence_link_after_login(): void
    {
        Storage::fake('evidence');
        $owner = User::factory()->create(['is_bug_hunter' => true]);
        $admin = User::factory()->create(['is_admin' => true, 'password' => bcrypt('password')]);
        $report = IncidentReport::create(['user_id' => $owner->id, 'tiket_no' => 'INS-2026-0003', 'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'test']);
        $attachment = LampiranInsiden::create(['laporan_id' => $report->id, 'jenis' => 'file', 'value' => 'bukti_laporan/copied.pdf']);
        Storage::disk('evidence')->put($attachment->value, 'proof');
        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
        $this->get(route('evidence.download', $attachment))->assertOk()->assertDownload('copied.pdf');
    }

    public function test_new_upload_is_stored_only_on_private_disk(): void
    {
        Storage::fake('evidence');
        Storage::fake('public');
        $this->post('/register', ['name' => 'Reporter', 'email' => 'owner@example.com', 'password' => 'password', 'password_confirmation' => 'password']);
        $this->post('/bug-hunter/laporan/agree', ['agree' => '1']);
        $this->withSession(['captcha_answer' => 7, 'captcha_q' => 'test'])->post('/bug-hunter/laporan/simpan', [
            'kategori_insiden' => 'Phishing', 'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://example.com', 'down_time' => '01:00',
            'deskripsi' => 'Test', 'tindakan_teknis' => 'Test', 'captcha_answer' => 7,
            'bukti' => [['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')]],
        ])->assertRedirect(route('bug-hunter.thank-you'));
        $attachment = LampiranInsiden::firstOrFail();
        Storage::disk('evidence')->assertExists($attachment->value);
        Storage::disk('public')->assertMissing($attachment->value);
        $this->get(route('evidence.download', $attachment))->assertOk();
    }
}
