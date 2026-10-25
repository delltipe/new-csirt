<?php

namespace App\Console\Commands;

use App\Models\SandboxIncident;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SandboxPruneIncidentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Note: Deletion is only executed when --execute is explicitly supplied.
     * Interactive confirmation defaults to "no" and cannot be bypassed.
     */
    protected $signature = 'sandbox:prune-incidents
                            {--days=14 : Nilai ambang retensi dalam hari (harus berupa bilangan bulat positif)}
                            {--execute : Menjalankan penghapusan fisik setelah konfirmasi (default adalah mode dry-run)}';

    /**
     * The console command description.
     */
    protected $description = 'Memeriksa dan membersihkan laporan insiden simulasi kadaluarsa pada basis data sandbox';

    /**
     * Protected demo tickets that must never be purged under any circumstances.
     */
    public const PROTECTED_TICKETS = [
        'DEMO-2026-B7TGZTCI',
        'DEMO-2026-CHH7JAMF',
    ];

    public function handle(): int
    {
        // 1. Validate retention days parameter
        $daysOption = $this->option('days');
        if (!is_numeric($daysOption) || (int) $daysOption <= 0 || filter_var($daysOption, FILTER_VALIDATE_INT) === false) {
            $this->error("Parameter --days tidak valid. Harus berupa bilangan bulat positif (contoh: --days=14). Diterima: '{$daysOption}'.");
            return self::FAILURE;
        }
        $days = (int) $daysOption;

        // 2. Fail-closed database isolation safeguards
        // Guard A: Default connection must not be 'sandbox'
        if (config('database.default') === 'sandbox' || DB::getDefaultConnection() === 'sandbox') {
            $this->error('Gagal pemeriksaan isolasi: Koneksi default aplikasi tidak boleh disetel ke [sandbox].');
            return self::FAILURE;
        }

        // Guard B: Sandbox connection configuration must exist
        $sandboxConfig = config('database.connections.sandbox');
        if (!is_array($sandboxConfig) || empty($sandboxConfig['driver'])) {
            $this->error('Gagal pemeriksaan isolasi: Konfigurasi koneksi [sandbox] tidak ditemukan atau tidak valid.');
            return self::FAILURE;
        }

        $sandboxDriver = $sandboxConfig['driver'];
        $isTesting = app()->environment('testing');

        if ($isTesting) {
            if (!in_array($sandboxDriver, ['sqlite', 'pgsql'], true)) {
                $this->error("Gagal pemeriksaan isolasi: Driver basis data pengujian tidak diizinkan ({$sandboxDriver}).");
                return self::FAILURE;
            }
            if ($sandboxDriver === 'sqlite' && ($sandboxConfig['database'] ?? '') !== ':memory:') {
                $this->error('Gagal pemeriksaan isolasi: Driver sqlite pada lingkungan pengujian harus menggunakan basis data :memory:.');
                return self::FAILURE;
            }
        } else {
            if ($sandboxDriver !== 'pgsql') {
                $this->error("Gagal pemeriksaan isolasi: Koneksi sandbox non-pengujian wajib menggunakan driver PostgreSQL (pgsql). Driver terdeteksi: {$sandboxDriver}.");
                return self::FAILURE;
            }
        }

        // Guard C: Verify sandbox connection can be resolved independently
        try {
            $connection = DB::connection('sandbox');
            if ($connection->getName() !== 'sandbox') {
                $this->error('Gagal pemeriksaan isolasi: Nama koneksi aktif yang diselesaikan bukan [sandbox].');
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('Gagal terhubung ke koneksi basis data [sandbox]: ' . $e->getMessage());
            return self::FAILURE;
        }

        // 3. Query candidate records using strict '<' comparison
        $cutoff = now()->subDays($days);
        $execute = (bool) $this->option('execute');

        $baseQuery = SandboxIncident::on('sandbox')
            ->where('created_at', '<', $cutoff);

        $totalExpiredCount = (clone $baseQuery)->count();

        $protectedCount = (clone $baseQuery)
            ->whereIn('tiket_no', self::PROTECTED_TICKETS)
            ->count();

        $eligibleQuery = (clone $baseQuery)
            ->whereNotIn('tiket_no', self::PROTECTED_TICKETS)
            ->orderBy('created_at', 'asc');

        $eligibleCount = (clone $eligibleQuery)->count();

        // 4. Default Dry-Run Mode (Read-Only)
        if (!$execute) {
            $this->info('=== Pemeriksaan Retensi Sandbox (DRY-RUN / HANYA-BACA) ===');
            $this->line("Ambang batas:       {$days} hari (laporan dibuat sebelum {$cutoff->toDateTimeString()})");
            $this->line("Koneksi sasaran:    sandbox ({$sandboxDriver})");
            $this->line("Total kadaluarsa:   {$totalExpiredCount} laporan");
            $this->line("Tiket dilindungi:   {$protectedCount} laporan dikecualikan dari pembersihan");
            $this->line("Kandidat dihapus:   {$eligibleCount} laporan");
            $this->newLine();

            if ($eligibleCount > 0) {
                $candidates = (clone $eligibleQuery)->get(['tiket_no', 'kategori_insiden', 'status', 'created_at']);
                $this->table(
                    ['No Tiket', 'Kategori', 'Status', 'Waktu Dibuat'],
                    $candidates->map(fn($row) => [
                        $row->tiket_no,
                        $row->kategori_insiden,
                        $row->statusLabel(),
                        $row->created_at?->toDateTimeString() ?? '-',
                    ])
                );
            } else {
                $this->line('Tidak ada laporan kadaluarsa yang memenuhi syarat untuk dibersihkan.');
            }

            $this->newLine();
            $this->comment('PEMBERITAHUAN: Mode dry-run aktif. Tidak ada rekaman data yang dihapus.');
            $this->line('Jalankan dengan opsi --execute untuk melakukan pembersihan nyata setelah konfirmasi interaktif.');

            return self::SUCCESS;
        }

        // 5. Execution Mode (--execute)
        $this->info('=== Pembersihan Retensi Sandbox (EKSEKUSI NYATA) ===');
        $this->line("Ambang batas:       {$days} hari (laporan dibuat sebelum {$cutoff->toDateTimeString()})");
        $this->line("Koneksi sasaran:    sandbox ({$sandboxDriver})");
        $this->line("Kandidat dihapus:   {$eligibleCount} laporan");
        $this->line("Tiket dilindungi:   {$protectedCount} laporan dikecualikan dari pembersihan");
        $this->newLine();

        if ($eligibleCount === 0) {
            $this->info('Tidak ada laporan kadaluarsa yang memenuhi kriteria untuk dihapus.');
            return self::SUCCESS;
        }

        $candidates = (clone $eligibleQuery)->get(['id', 'tiket_no', 'kategori_insiden', 'status', 'created_at']);
        $candidateIds = $candidates->pluck('id')->filter()->all();

        if (empty($candidateIds)) {
            $this->info('Tidak ada laporan kadaluarsa yang memenuhi kriteria untuk dihapus.');
            return self::SUCCESS;
        }

        $this->table(
            ['No Tiket', 'Kategori', 'Status', 'Waktu Dibuat'],
            $candidates->map(fn($row) => [
                $row->tiket_no,
                $row->kategori_insiden,
                $row->statusLabel(),
                $row->created_at?->toDateTimeString() ?? '-',
            ])
        );
        $this->newLine();

        // Interactive confirmation: default is FALSE (No)
        $confirmMessage = "PERINGATAN: Anda akan menghapus secara permanen {$eligibleCount} laporan sandbox di atas. Lanjutkan?";
        if (!$this->confirm($confirmMessage, false)) {
            $this->warn('Pembersihan dibatalkan. Tidak ada rekaman data yang dihapus.');
            return self::SUCCESS;
        }

        try {
            $deleted = DB::connection('sandbox')->transaction(function () use ($candidateIds, $cutoff) {
                return SandboxIncident::on('sandbox')
                    ->whereIn('id', $candidateIds)
                    ->where('created_at', '<', $cutoff)
                    ->whereNotIn('tiket_no', self::PROTECTED_TICKETS)
                    ->delete();
            });

            $this->info("Pembersihan berhasil: {$deleted} laporan sandbox kadaluarsa telah dihapus.");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal mengeksekusi penghapusan basis data sandbox: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
