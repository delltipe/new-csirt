<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\LampiranInsiden;
use App\Models\TacAgreement;
use App\Support\MathCaptcha;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BugHunterController extends Controller
{
    public const TAC_VERSION = '2026.08';

    public const CATEGORIES = [
        'Website Defacement',
        'Phishing',
        'Malware / Ransomware',
        'Kebocoran Data',
        'DDoS / Penolakan Layanan',
        'SQL Injection / XSS',
        'Social Engineering',
        'Lainnya',
    ];

    public function dashboard()
    {
        $reports = IncidentReport::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('bug-hunter.dashboard', compact('reports'));
    }

    public function show(int $id)
    {
        $report = IncidentReport::with('attachments')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('bug-hunter.show', compact('report'));
    }

    public function showTac()
    {
        $agreed = $this->hasCurrentAgreement();

        if ($agreed) {
            return redirect()->route('bug-hunter.create');
        }

        return view('bug-hunter.tac', ['version' => self::TAC_VERSION]);
    }

    public function agreeTac(Request $request)
    {
        $request->validate([
            'agree' => 'accepted',
        ], [
            'agree.accepted' => 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.',
        ]);

        try {
            TacAgreement::updateOrCreate(
                ['user_id' => auth()->id(), 'version' => self::TAC_VERSION],
                ['agreed_at' => now()]
            );
        } catch (\Exception $e) {
            return back()->withErrors([
                'agree' => 'Gagal menyimpan persetujuan. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('bug-hunter.create');
    }

    private function hasCurrentAgreement(): bool
    {
        return TacAgreement::where('user_id', auth()->id())
            ->where('version', self::TAC_VERSION)->exists();
    }

    public function create()
    {
        if (! $this->hasCurrentAgreement()) {
            return redirect()->route('bug-hunter.tac');
        }

        $captchaQuestion = MathCaptcha::question();

        return view('bug-hunter.create', [
            'categories' => self::CATEGORIES,
            'captchaQuestion' => $captchaQuestion,
        ]);
    }

    public function store(Request $request)
    {
        if (! $this->hasCurrentAgreement()) {
            return redirect()->route('bug-hunter.tac');
        }

        $validated = $request->validate([
            'kategori_insiden' => 'required|string|in:' . implode(',', self::CATEGORIES),
            'waktu_kejadian' => 'required|date',
            'lokasi_url' => 'required|url|max:255',
            'down_time' => 'required|date_format:H:i',
            // No max length on free-text: researchers may paste pentest payloads,
            // stack traces, or PoC snippets. Stored via bound parameters and
            // escaped with {{ }} on render — never blocked, never raw SQL.
            'deskripsi' => 'required|string',
            'tindakan_teknis' => 'required|string',
            'bukti' => 'nullable|array|max:3',
            'bukti.*.jenis' => 'nullable|in:file,url',
            'bukti.*.file' => 'nullable|file|mimes:png,jpg,jpeg,gif,pdf|max:5120',
            'bukti.*.url' => 'nullable|url|max:255',
            'captcha_answer' => 'required|integer',
        ], [
            'kategori_insiden.required' => 'Pilih kategori insiden dari daftar yang tersedia.',
            'kategori_insiden.in' => 'Pilih kategori insiden dari daftar yang tersedia.',
            'waktu_kejadian.required' => 'Isi waktu kejadian menggunakan pemilih tanggal dan jam.',
            'waktu_kejadian.date' => 'Format waktu kejadian tidak valid. Pilih ulang tanggal dan jam.',
            'lokasi_url.required' => 'Isi URL lokasi insiden, diawali http:// atau https://.',
            'lokasi_url.url' => 'URL tidak valid. Contoh yang benar: https://portal.jakarta.go.id/halaman/contoh.',
            'lokasi_url.max' => 'URL terlalu panjang (maks. 255 karakter). Gunakan URL yang lebih pendek.',
            'down_time.required' => 'Isi durasi down time dalam format jam:menit, mis. 02:15.',
            'down_time.date_format' => 'Format down time harus jam:menit (24 jam), mis. 02:15.',
            'deskripsi.required' => 'Isi deskripsi kejadian: kronologi, dampak, dan langkah reproduksi bila ada.',
            'tindakan_teknis.required' => 'Isi tindakan teknis yang sudah dilakukan atau direkomendasikan.',
            'bukti.max' => 'Maksimal 3 bukti. Hapus baris bukti yang berlebih.',
            'bukti.*.jenis.in' => 'Jenis bukti harus File atau URL.',
            'bukti.*.file.mimes' => 'Format file harus PNG, JPG, JPEG, GIF, atau PDF.',
            'bukti.*.file.max' => 'Ukuran file maksimal 5MB. Kompres atau gunakan URL sebagai gantinya.',
            'bukti.*.url.url' => 'URL bukti tidak valid. Awali dengan http:// atau https://.',
            'captcha_answer.required' => 'Isi jawaban verifikasi matematika.',
            'captcha_answer.integer' => 'Jawaban verifikasi harus berupa angka.',
        ]);

        if (! MathCaptcha::verify($validated['captcha_answer'])) {
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'captcha_answer' => 'Jawaban verifikasi salah. Silakan coba lagi.',
            ]);
        }

        $attachments = [];
        $createdFiles = [];
        try {
            foreach ($request->input('bukti', []) as $index => $row) {
                $jenis = $row['jenis'] ?? null;
                if ($jenis === 'file' && $request->hasFile("bukti.$index.file")) {
                    $path = $request->file("bukti.$index.file")->store('bukti_laporan', 'evidence');
                    if ($path === false) {
                        throw new \RuntimeException('Failed to store incident attachment.');
                    }
                    $createdFiles[] = $path;
                    $attachments[] = ['jenis' => 'file', 'value' => $path];
                } elseif ($jenis === 'url' && ! empty($row['url'])) {
                    $attachments[] = ['jenis' => 'url', 'value' => $row['url']];
                }
            }

            // Calculate outside the transaction so SQLite does not need to upgrade a
            // read lock to a write lock. The unique index remains the final arbiter.
            for ($attempt = 0; $attempt < 5; $attempt++) {
                try {
                    $ticket = $this->generateTiketNo();
                    $report = DB::transaction(function () use ($validated, $attachments, $ticket) {
                        $report = IncidentReport::create([
                            'user_id' => auth()->id(),
                            'tiket_no' => $ticket,
                            'kategori_insiden' => $validated['kategori_insiden'],
                            'waktu_kejadian' => $validated['waktu_kejadian'],
                            'lokasi_url' => $validated['lokasi_url'],
                            'down_time' => $validated['down_time'],
                            'deskripsi' => $validated['deskripsi'],
                            'tindakan_teknis' => $validated['tindakan_teknis'],
                            'status' => IncidentReport::STATUS_PENDING,
                        ]);
                        foreach ($attachments as $attachment) {
                            LampiranInsiden::create([
                                'laporan_id' => $report->id,
                                'jenis' => $attachment['jenis'],
                                'value' => $attachment['value'],
                            ]);
                        }
                        return $report;
                    });
                    break;
                } catch (QueryException $e) {
                    // Retry only ticket collisions or SQLite's transient busy/locked errors.
                    $ticketConflict = $e instanceof UniqueConstraintViolationException
                        && str_contains(strtolower($e->getMessage()), 'tiket_no');
                    $sqliteBusy = DB::connection()->getDriverName() === 'sqlite'
                        && in_array((int) ($e->errorInfo[1] ?? 0), [5, 6], true);
                    if ((! $ticketConflict && ! $sqliteBusy) || $attempt === 4) {
                        throw $e;
                    }
                    usleep(50000 * ($attempt + 1));
                }
            }
        } catch (\Throwable $e) {
            foreach ($createdFiles as $path) {
                try {
                    if (! Storage::disk('evidence')->delete($path)) {
                        \Log::error('Failed to clean up incident upload.', ['path' => $path]);
                    }
                } catch (\Throwable $cleanupError) {
                    report($cleanupError);
                }
            }
            report($e);
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'deskripsi' => 'Gagal menyimpan laporan. Silakan coba lagi atau hubungi CSIRT langsung.',
            ]);
        }

        MathCaptcha::forget();

        return redirect()->route('bug-hunter.thank-you')->with('tiket_no', $report->tiket_no);
    }

    public function thankYou()
    {
        $tiketNo = session('tiket_no');

        return view('bug-hunter.thank-you', compact('tiketNo'));
    }

    protected function generateTiketNo(): string
    {
        $year = now()->year;
        $prefix = "INS-{$year}-";
        $highest = IncidentReport::withTrashed()
            ->where('tiket_no', 'like', $prefix . '%')
            ->pluck('tiket_no')
            ->reduce(function (int $max, string $ticket) use ($prefix) {
                $suffix = substr($ticket, strlen($prefix));
                return preg_match('/^\d{4}$/', $suffix) ? max($max, (int) $suffix) : $max;
            }, 0);

        if ($highest >= 9999) {
            throw new \RuntimeException('Annual incident ticket limit reached.');
        }

        return sprintf('INS-%s-%04d', $year, $highest + 1);
    }
}
