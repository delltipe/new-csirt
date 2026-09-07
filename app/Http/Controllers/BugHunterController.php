<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\LampiranInsiden;
use App\Models\TacAgreement;
use App\Support\MathCaptcha;
use Illuminate\Http\Request;

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
        $agreed = TacAgreement::where('user_id', auth()->id())
            ->where('version', self::TAC_VERSION)
            ->exists();

        if ($agreed) {
            return redirect()->route('bug-hunter.create');
        }

        return view('bug-hunter.tac', ['version' => self::TAC_VERSION]);
    }

    public function agreeTac(Request $request)
    {
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

    public function create()
    {
        $captchaQuestion = MathCaptcha::question();

        return view('bug-hunter.create', [
            'categories' => self::CATEGORIES,
            'captchaQuestion' => $captchaQuestion,
        ]);
    }

    public function store(Request $request)
    {
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
        foreach ($request->input('bukti', []) as $index => $row) {
            $jenis = $row['jenis'] ?? null;

            if ($jenis === 'file' && $request->hasFile("bukti.$index.file")) {
                $attachments[] = [
                    'jenis' => 'file',
                    'value' => $request->file("bukti.$index.file")->store('bukti_laporan', 'public'),
                ];
            } elseif ($jenis === 'url' && !empty($row['url'])) {
                $attachments[] = ['jenis' => 'url', 'value' => $row['url']];
            }
        }

        try {
            $report = IncidentReport::create([
                'user_id' => auth()->id(),
                'tiket_no' => $this->generateTiketNo(),
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
        } catch (\Exception $e) {
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

        do {
            $seq = IncidentReport::where('tiket_no', 'like', "INS-{$year}-%")->count() + 1;
            $tiketNo = sprintf('INS-%s-%04d', $year, $seq);
        } while (IncidentReport::where('tiket_no', $tiketNo)->exists());

        return $tiketNo;
    }
}
