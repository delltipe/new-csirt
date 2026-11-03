<?php

namespace Tests\Feature;

use App\Http\Controllers\BugHunterController;
use App\Models\HeroSlide;
use App\Models\IncidentReport;
use App\Models\LampiranInsiden;
use App\Models\TacAgreement;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IncidentCorrectnessTest extends TestCase
{
    use RefreshDatabase;

    private function reporter(): User
    {
        $user = User::factory()->create(['is_bug_hunter' => true]);
        $this->actingAs($user);
        return $user;
    }

    private function agree(User $user, string $version = BugHunterController::TAC_VERSION): void
    {
        TacAgreement::create(['user_id' => $user->id, 'version' => $version, 'agreed_at' => now()]);
    }

    private function data(array $extra = []): array
    {
        return array_merge([
            'kategori_insiden' => 'Phishing', 'waktu_kejadian' => '2026-08-11T10:30',
            'lokasi_url' => 'https://example.com', 'down_time' => '01:00',
            'deskripsi' => 'Deskripsi', 'tindakan_teknis' => 'Tindakan', 'captcha_answer' => 7,
        ], $extra);
    }

    private function existing(User $user, string $ticket): IncidentReport
    {
        return IncidentReport::create(['user_id' => $user->id, 'tiket_no' => $ticket,
            'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'Test']);
    }

    private function submit(array $extra = [])
    {
        return $this->withSession(['captcha_answer' => 7, 'captcha_q' => 'test'])
            ->post(route('bug-hunter.store'), $this->data($extra));
    }

    public function test_agreement_is_scoped_to_user_and_current_version_for_get_and_post(): void
    {
        Storage::fake('evidence');
        $user = $this->reporter();
        $other = User::factory()->create();
        $this->agree($other);
        $this->agree($user, 'old');
        $report = $this->existing($user, 'INS-2026-0001');
        $this->get(route('bug-hunter.create'))->assertRedirect(route('bug-hunter.tac'));
        $this->submit(['bukti' => [['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')]]])
            ->assertRedirect(route('bug-hunter.tac'));
        $this->assertSame(1, IncidentReport::count());
        $this->assertSame([], Storage::disk('evidence')->allFiles());
        $this->get(route('bug-hunter.dashboard'))->assertOk();
        $this->get(route('bug-hunter.show', $report->id))->assertOk();
        $this->agree($user);
        $this->get(route('bug-hunter.create'))->assertOk();
        $this->submit()->assertRedirect(route('bug-hunter.thank-you'));
    }

    public function test_ticket_uses_highest_retained_number_and_resets_each_year(): void
    {
        $this->travelTo(now()->setDate(2026, 6, 1));
        $user = $this->reporter(); $this->agree($user);
        $this->existing($user, 'INS-2026-0001')->delete();
        $this->existing($user, 'INS-2026-0002')->delete();
        $this->existing($user, 'INS-2026-0003')->delete();
        $this->submit()->assertRedirect(route('bug-hunter.thank-you'));
        $this->assertDatabaseHas('lapor_insiden', ['tiket_no' => 'INS-2026-0004']);
        $this->travelTo(now()->setDate(2027, 1, 1));
        $this->submit()->assertRedirect(route('bug-hunter.thank-you'));
        $this->assertDatabaseHas('lapor_insiden', ['tiket_no' => 'INS-2027-0001']);
    }

    public function test_deleted_earlier_ticket_does_not_reuse_or_loop(): void
    {
        $this->travelTo(now()->setDate(2026, 6, 1));
        $user = $this->reporter(); $this->agree($user);
        $this->existing($user, 'INS-2026-0001')->delete();
        $this->existing($user, 'INS-2026-0002');
        $this->existing($user, 'INS-2026-0003');
        $this->submit()->assertRedirect(route('bug-hunter.thank-you'));
        $this->assertDatabaseHas('lapor_insiden', ['tiket_no' => 'INS-2026-0004']);
    }

    public function test_limit_and_bounded_ticket_conflict_do_not_leave_files(): void
    {
        $this->travelTo(now()->setDate(2026, 6, 1));
        Storage::fake('evidence');
        $user = $this->reporter(); $this->agree($user);
        $this->existing($user, 'INS-2026-9999')->delete();
        $this->submit()->assertSessionHasErrors('deskripsi');
        $this->assertSame(1, IncidentReport::withTrashed()->count());
        $this->travelTo(now()->setDate(2027, 1, 1));
        $attempts = 0;
        IncidentReport::creating(function () use (&$attempts) {
            $attempts++;
            throw new UniqueConstraintViolationException('sqlite', 'insert into lapor_insiden (tiket_no)', [], new \Exception('UNIQUE constraint failed: lapor_insiden.tiket_no'));
        });
        $this->submit(['bukti' => [['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')]]])->assertSessionHasErrors('deskripsi');
        $this->assertSame(5, $attempts);
        $this->assertSame([], Storage::disk('evidence')->allFiles());
        IncidentReport::flushEventListeners();
    }

    public function test_ticket_collision_is_retried_and_succeeds(): void
    {
        $this->travelTo(now()->setDate(2026, 6, 1));
        $user = $this->reporter(); $this->agree($user);
        $attempts = 0;
        IncidentReport::creating(function () use (&$attempts) {
            if (++$attempts === 1) {
                throw new UniqueConstraintViolationException('sqlite', 'insert into lapor_insiden (tiket_no)', [], new \Exception('UNIQUE constraint failed: lapor_insiden.tiket_no'));
            }
        });
        $this->submit()->assertRedirect(route('bug-hunter.thank-you'));
        $this->assertSame(2, $attempts);
        $this->assertSame(1, IncidentReport::count());
        IncidentReport::flushEventListeners();
    }

    public function test_report_insert_failure_cleans_up_upload(): void
    {
        Storage::fake('evidence');
        $user = $this->reporter(); $this->agree($user);
        IncidentReport::creating(fn () => throw new \RuntimeException('report failure'));
        $this->submit(['bukti' => [['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')]]])->assertSessionHasErrors('deskripsi');
        $this->assertSame(0, IncidentReport::count());
        $this->assertSame([], Storage::disk('evidence')->allFiles());
        IncidentReport::flushEventListeners();
    }

    public function test_storage_failure_is_recoverable(): void
    {
        $disk = \Mockery::mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $disk->shouldReceive('putFileAs')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('evidence')->andReturn($disk);
        $user = $this->reporter(); $this->agree($user);
        $this->submit(['bukti' => [['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')]]])->assertSessionHasErrors('deskripsi');
        $this->assertSame(0, IncidentReport::count());
        $this->assertSame(0, LampiranInsiden::count());
    }

    public function test_attachment_failure_rolls_back_rows_and_only_new_files(): void
    {
        Storage::fake('evidence'); Storage::disk('evidence')->put('bukti_laporan/retained.pdf', 'evidence');
        $user = $this->reporter(); $this->agree($user);
        $count = 0;
        LampiranInsiden::creating(function () use (&$count) { if (++$count === 2) throw new \RuntimeException('insert failure'); });
        $this->submit(['bukti' => [
            ['jenis' => 'file', 'file' => UploadedFile::fake()->create('proof.pdf', 1, 'application/pdf')],
            ['jenis' => 'url', 'url' => 'https://example.com/proof'],
        ]])->assertSessionHasErrors('deskripsi');
        $this->assertSame(0, IncidentReport::count());
        $this->assertSame(0, LampiranInsiden::count());
        $this->assertSame(['bukti_laporan/retained.pdf'], Storage::disk('evidence')->allFiles());
        LampiranInsiden::flushEventListeners();
    }

    public function test_hero_update_and_delete_remove_only_unreferenced_files(): void
    {
        Storage::fake('public'); Storage::disk('public')->put('hero/old.jpg', 'old');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $slide = HeroSlide::create(['judul' => 'Old', 'gambar' => 'hero/old.jpg']);
        $other = HeroSlide::create(['judul' => 'Other', 'gambar' => 'hero/old.jpg']);
        $this->post(route('admin.hero.update', $slide->id), [
            'judul' => 'New', 'gambar' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect(route('admin.dashboard'));
        $newPath = $slide->fresh()->gambar;
        Storage::disk('public')->assertExists('hero/old.jpg');
        Storage::disk('public')->assertExists($newPath);
        $this->post(route('admin.hero.delete', $other->id))->assertRedirect(route('admin.dashboard'));
        Storage::disk('public')->assertMissing('hero/old.jpg');
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_failed_hero_update_and_delete_preserve_old_image(): void
    {
        Storage::fake('public'); Storage::disk('public')->put('hero/old.jpg', 'old');
        $admin = User::factory()->create(['is_admin' => true]); $this->actingAs($admin);
        $slide = HeroSlide::create(['judul' => 'Old', 'gambar' => 'hero/old.jpg']);
        HeroSlide::updating(fn () => throw new \RuntimeException('update failure'));
        $this->post(route('admin.hero.update', $slide->id), [
            'judul' => 'New', 'gambar' => UploadedFile::fake()->image('new.jpg'),
        ])->assertSessionHasErrors('judul');
        $this->assertSame('hero/old.jpg', $slide->fresh()->gambar);
        $this->assertSame(['hero/old.jpg'], Storage::disk('public')->allFiles());
        HeroSlide::flushEventListeners();
        HeroSlide::deleting(fn () => throw new \RuntimeException('delete failure'));
        $this->post(route('admin.hero.delete', $slide->id))->assertSessionHasErrors('judul');
        Storage::disk('public')->assertExists('hero/old.jpg');
        $this->assertNotNull($slide->fresh());
        HeroSlide::flushEventListeners();
    }
}
