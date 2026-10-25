<?php

namespace Tests\Feature;

use App\Console\Commands\SandboxPruneIncidentsCommand;
use App\Models\SandboxIncident;
use Database\Seeders\PublicPreviewSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SandboxPruneIncidentsTest extends TestCase
{
    use RefreshDatabase;

    protected Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PublicPreviewSeeder::class);

        // Lock test execution to a fixed, deterministic point in time
        $this->now = Carbon::parse('2026-10-25 12:00:00');
        Carbon::setTestNow($this->now);

        // Ensure the sandbox migration is run on the sandbox connection (in-memory SQLite test database)
        if (!Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->up();
        }
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        if (Schema::connection('sandbox')->hasTable('sandbox_incidents')) {
            $migration = require database_path('migrations/sandbox/2026_10_24_000001_create_sandbox_incidents_table.php');
            $migration->down();
        }

        parent::tearDown();
    }

    /**
     * Helper to create a test incident with explicit creation timestamp.
     */
    protected function createIncident(string $tiketNo, Carbon $createdAt, array $overrides = []): SandboxIncident
    {
        $incident = SandboxIncident::on('sandbox')->create(array_merge([
            'tiket_no' => $tiketNo,
            'kategori_insiden' => 'Website Defacement',
            'waktu_kejadian' => $createdAt->copy()->subHours(2),
            'lokasi_url' => 'https://contoh-demo.jakarta.go.id/halaman-uji',
            'down_time' => '01:00',
            'deskripsi' => 'Pengujian retensi sandbox fiktif.',
            'tindakan_teknis' => 'Mitigasi pengujian otomatis.',
            'bukti_urls' => ['https://example.com/bukti-1'],
            'status' => SandboxIncident::STATUS_PENDING,
        ], $overrides));

        $incident->created_at = $createdAt;
        $incident->updated_at = $createdAt;
        $incident->save();

        return $incident;
    }

    /**
     * Case 1: Default execution reports eligible expired records but deletes nothing.
     */
    public function test_default_execution_reports_eligible_expired_records_but_deletes_nothing(): void
    {
        $expiredDate = $this->now->copy()->subDays(20);
        $this->createIncident('DEMO-2026-EXP00001', $expiredDate);

        $this->assertEquals(1, SandboxIncident::on('sandbox')->count());

        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(0)
            ->expectsOutputToContain('DRY-RUN / HANYA-BACA')
            ->expectsOutputToContain('Kandidat dihapus:   1 laporan')
            ->expectsOutputToContain('DEMO-2026-EXP00001')
            ->expectsOutputToContain('Mode dry-run aktif. Tidak ada rekaman data yang dihapus.');

        // Verify record is strictly preserved
        $this->assertEquals(1, SandboxIncident::on('sandbox')->count());
        $this->assertNotNull(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-EXP00001')->first());
    }

    /**
     * Case 2: Both protected demonstration tickets survive dry-run and confirmed cleanup.
     */
    public function test_both_protected_demonstration_tickets_survive_dry_run_and_confirmed_cleanup(): void
    {
        $oldDate = $this->now->copy()->subDays(45);

        // Create both protected tickets with timestamps far older than the 14-day threshold
        $this->createIncident('DEMO-2026-B7TGZTCI', $oldDate);
        $this->createIncident('DEMO-2026-CHH7JAMF', $oldDate);
        // Create an unprotected expired ticket
        $this->createIncident('DEMO-2026-PURGE001', $oldDate);

        $this->assertEquals(3, SandboxIncident::on('sandbox')->count());

        // Dry-run inspection verifies protected exclusion
        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(0)
            ->expectsOutputToContain('Total kadaluarsa:   3 laporan')
            ->expectsOutputToContain('Tiket dilindungi:   2 laporan dikecualikan dari pembersihan')
            ->expectsOutputToContain('Kandidat dihapus:   1 laporan')
            ->expectsOutputToContain('DEMO-2026-PURGE001');

        $this->assertEquals(3, SandboxIncident::on('sandbox')->count());

        // Confirmed execution
        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pembersihan berhasil: 1 laporan sandbox kadaluarsa telah dihapus.');

        // Protected tickets must remain in database; unprotected must be deleted
        $this->assertEquals(2, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-B7TGZTCI')->exists());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-CHH7JAMF')->exists());
        $this->assertFalse(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-PURGE001')->exists());
    }

    /**
     * Case 3: Records younger than the retention threshold are preserved.
     */
    public function test_records_younger_than_retention_threshold_are_preserved(): void
    {
        $freshDate = $this->now->copy()->subDays(5);
        $expiredDate = $this->now->copy()->subDays(20);

        $this->createIncident('DEMO-2026-FRESH001', $freshDate);
        $this->createIncident('DEMO-2026-STALE001', $expiredDate);

        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0);

        $this->assertEquals(1, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-FRESH001')->exists());
        $this->assertFalse(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-STALE001')->exists());
    }

    /**
     * Case 4: Records exactly on or newer than the threshold are not treated as older records.
     */
    public function test_records_exactly_on_or_newer_than_threshold_are_not_treated_as_older(): void
    {
        // Default threshold is 14 days -> cutoff = now()->subDays(14) = 2026-10-11 12:00:00
        $cutoff = $this->now->copy()->subDays(14);

        $exactOnCutoff = $cutoff->copy();
        $oneSecondNewer = $cutoff->copy()->addSecond();
        $oneSecondOlder = $cutoff->copy()->subSecond();

        $this->createIncident('DEMO-2026-EXACT001', $exactOnCutoff);
        $this->createIncident('DEMO-2026-NEWER001', $oneSecondNewer);
        $this->createIncident('DEMO-2026-OLDER001', $oneSecondOlder);

        // Dry-run must identify ONLY the record created strictly before cutoff (< cutoff)
        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(0)
            ->expectsOutputToContain('Kandidat dihapus:   1 laporan')
            ->expectsOutputToContain('DEMO-2026-OLDER001');

        // Confirmed execution
        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0);

        $this->assertEquals(2, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-EXACT001')->exists());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-NEWER001')->exists());
        $this->assertFalse(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-OLDER001')->exists());
    }

    /**
     * Case 5: Execution without explicit --execute never deletes records.
     */
    public function test_execution_without_explicit_execute_never_deletes_records(): void
    {
        $this->createIncident('DEMO-2026-TESTNOEX', $this->now->copy()->subDays(30));

        $this->artisan('sandbox:prune-incidents', ['--days' => 14])
            ->assertExitCode(0);

        $this->assertEquals(1, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-TESTNOEX')->exists());
    }

    /**
     * Case 6: --execute followed by a declined confirmation deletes nothing.
     */
    public function test_execute_followed_by_declined_confirmation_deletes_nothing(): void
    {
        $this->createIncident('DEMO-2026-TESTDECL', $this->now->copy()->subDays(25));

        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'no')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pembersihan dibatalkan. Tidak ada rekaman data yang dihapus.');

        $this->assertEquals(1, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-TESTDECL')->exists());
    }

    /**
     * Case 7: --execute with affirmative confirmation removes only expired, unprotected sandbox incidents.
     */
    public function test_execute_with_affirmative_confirmation_removes_only_expired_unprotected_incidents(): void
    {
        $oldDate = $this->now->copy()->subDays(20);
        $freshDate = $this->now->copy()->subDays(2);

        $this->createIncident('DEMO-2026-B7TGZTCI', $oldDate); // Protected
        $this->createIncident('DEMO-2026-EXPIRED1', $oldDate);  // Should be deleted
        $this->createIncident('DEMO-2026-EXPIRED2', $oldDate);  // Should be deleted
        $this->createIncident('DEMO-2026-FRESH002', $freshDate);// Should be preserved

        $this->assertEquals(4, SandboxIncident::on('sandbox')->count());

        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 2 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pembersihan berhasil: 2 laporan sandbox kadaluarsa telah dihapus.');

        $this->assertEquals(2, SandboxIncident::on('sandbox')->count());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-B7TGZTCI')->exists());
        $this->assertTrue(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-FRESH002')->exists());
        $this->assertFalse(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-EXPIRED1')->exists());
        $this->assertFalse(SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-EXPIRED2')->exists());
    }

    /**
     * Case 8: Invalid retention values are rejected safely.
     */
    public function test_invalid_retention_values_are_rejected_safely(): void
    {
        $invalidValues = ['0', '-5', 'abc', '14.5'];

        foreach ($invalidValues as $val) {
            $this->artisan('sandbox:prune-incidents', ['--days' => $val])
                ->assertExitCode(1)
                ->expectsOutputToContain('Parameter --days tidak valid');
        }
    }

    /**
     * Case 9: Database isolation safeguards prevent operation when configured connection is unsafe.
     */
    public function test_database_isolation_safeguards_prevent_operation_when_configured_connection_is_unsafe(): void
    {
        // 1. Refuse if default connection is set to 'sandbox'
        Config::set('database.default', 'sandbox');
        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(1)
            ->expectsOutputToContain('Koneksi default aplikasi tidak boleh disetel ke [sandbox]');

        // Reset default connection
        Config::set('database.default', 'sqlite');

        // 2. Refuse if sandbox driver is unsupported or invalid
        Config::set('database.connections.sandbox.driver', 'unsupported_driver');
        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(1)
            ->expectsOutputToContain('Driver basis data pengujian tidak diizinkan');

        // 3. Refuse if sandbox configuration is missing
        Config::set('database.connections.sandbox', null);
        $this->artisan('sandbox:prune-incidents')
            ->assertExitCode(1)
            ->expectsOutputToContain('Konfigurasi koneksi [sandbox] tidak ditemukan atau tidak valid');
    }

    /**
     * Case 10: Default SQLite showcase tables remain completely unchanged.
     */
    public function test_default_sqlite_showcase_tables_remain_unchanged(): void
    {
        $oldDate = $this->now->copy()->subDays(25);
        $this->createIncident('DEMO-2026-STALE999', $oldDate);

        $tables = [
            'berita_siber',
            'events',
            'infografis_keamanan',
            'peringatan_keamanan',
            'peraturan_kebijakan',
            'panduan_teknis',
            'slide_hero',
            'users',
            'lapor_insiden',
            'lampiran_insiden',
            'tac_agreements',
            'contact_us',
        ];

        $initialCounts = [];
        foreach ($tables as $table) {
            $initialCounts[$table] = DB::connection('sqlite')->table($table)->count();
        }

        // Execute cleanup
        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0);

        // Verify sandbox record deleted
        $this->assertEquals(0, SandboxIncident::on('sandbox')->count());

        // Verify every showcase table is completely identical
        foreach ($tables as $table) {
            $this->assertSame(
                $initialCounts[$table],
                DB::connection('sqlite')->table($table)->count(),
                "Tabel showcase [{$table}] mengalami mutasi yang tidak diinginkan."
            );
        }
    }

    /**
     * Case 11: Candidate-ID scoping preserves late-arriving expired records inserted during execution.
     */
    public function test_candidate_id_scoping_preserves_late_arriving_expired_records(): void
    {
        $oldDate = $this->now->copy()->subDays(30);

        // 1. Initial state: create both protected demo tickets and one initial expired candidate
        $this->createIncident('DEMO-2026-B7TGZTCI', $oldDate);
        $this->createIncident('DEMO-2026-CHH7JAMF', $oldDate);
        $this->createIncident('DEMO-2026-INITIAL1', $oldDate);

        $lateArrivalInserted = false;

        // 2. Deterministic hook: listen for the query that captures candidate IDs
        DB::connection('sandbox')->listen(function ($query) use (&$lateArrivalInserted, $oldDate) {
            // Check for the specific SELECT query that captures candidate IDs
            if (
                !$lateArrivalInserted &&
                stripos($query->sql, 'select') !== false &&
                stripos($query->sql, '"id"') !== false &&
                stripos($query->sql, '"tiket_no"') !== false
            ) {
                $lateArrivalInserted = true;

                // Simulate an unexpected concurrent write of a backdated expired record
                DB::connection('sandbox')->table('sandbox_incidents')->insert([
                    'tiket_no' => 'DEMO-2026-LATEARRIVE',
                    'kategori_insiden' => 'Website Defacement',
                    'waktu_kejadian' => $oldDate->copy()->subHours(2)->toDateTimeString(),
                    'lokasi_url' => 'https://contoh-demo.jakarta.go.id/concurrent',
                    'down_time' => '00:30',
                    'deskripsi' => 'Insiden fiktif konkuren yang tiba setelah penangkapan kandidat ID.',
                    'tindakan_teknis' => 'Mitigasi pengujian konkuren.',
                    'bukti_urls' => json_encode(['https://example.com/bukti']),
                    'status' => SandboxIncident::STATUS_PENDING,
                    'created_at' => $oldDate->toDateTimeString(),
                    'updated_at' => $oldDate->toDateTimeString(),
                ]);
            }
        });

        // 3. Run command with --execute and affirmative confirmation
        // Only 1 candidate was presented in the prompt table (the initial candidate)
        $this->artisan('sandbox:prune-incidents', ['--execute' => true])
            ->expectsConfirmation('PERINGATAN: Anda akan menghapus secara permanen 1 laporan sandbox di atas. Lanjutkan?', 'yes')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pembersihan berhasil: 1 laporan sandbox kadaluarsa telah dihapus.');

        // 4. Verify the deterministic hook actually triggered
        $this->assertTrue($lateArrivalInserted, 'The deterministic query hook must fire during candidate ID capture.');

        // 5. Assert the originally reviewed candidate is deleted
        $this->assertFalse(
            SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-INITIAL1')->exists(),
            'The reviewed candidate should be deleted.'
        );

        // 6. Assert the late-arriving expired record is preserved because its ID was not in the reviewed candidate list
        $this->assertTrue(
            SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-LATEARRIVE')->exists(),
            'The late-arriving record must be preserved because its ID was not captured in the reviewed candidate IDs.'
        );

        // 7. Assert both protected demo tickets remain intact
        $this->assertTrue(
            SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-B7TGZTCI')->exists(),
            'Protected ticket DEMO-2026-B7TGZTCI must remain in database.'
        );
        $this->assertTrue(
            SandboxIncident::on('sandbox')->where('tiket_no', 'DEMO-2026-CHH7JAMF')->exists(),
            'Protected ticket DEMO-2026-CHH7JAMF must remain in database.'
        );

        // Total count in sandbox should now be 3 (2 protected + 1 late-arriving)
        $this->assertEquals(3, SandboxIncident::on('sandbox')->count());
    }
}
