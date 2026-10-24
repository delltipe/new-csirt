<?php

namespace App\Http\Controllers;

use App\Models\SandboxIncident;
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
        abort(403, 'Dasbor pelapor dinonaktifkan pada versi pratinjau publik.');
    }

    public function show(int $id)
    {
        abort(403, 'Detail laporan perorangan dinonaktifkan pada versi pratinjau publik.');
    }

    public function showTac()
    {
        return redirect()->route('bug-hunter.create');
    }

    public function agreeTac(Request $request)
    {
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
        // Strictly reject binary file uploads in this demo sandbox workflow
        if ($request->hasFile('bukti') || count($request->allFiles()) > 0) {
            return back()->withInput()->withErrors([
                'bukti' => 'Pengunggahan berkas biner dinonaktifkan pada mode pratinjau publik. Gunakan tautan URL bukti saja.',
            ]);
        }

        $validated = $request->validate([
            'kategori_insiden' => 'required|string|in:' . implode(',', self::CATEGORIES),
            'waktu_kejadian' => 'required|date',
            'lokasi_url' => 'required|url:http,https|max:255',
            'down_time' => 'required|date_format:H:i',
            'deskripsi' => 'required|string|max:5000',
            'tindakan_teknis' => 'required|string|max:5000',
            'bukti' => 'nullable|array|max:3',
            'bukti.*.url' => 'nullable|url:http,https|max:255',
            'captcha_answer' => 'required|integer',
            'syarat_ketentuan' => 'accepted',
        ], [
            'kategori_insiden.required' => 'Pilih kategori insiden dari daftar yang tersedia.',
            'kategori_insiden.in' => 'Pilih kategori insiden dari daftar yang tersedia.',
            'waktu_kejadian.required' => 'Isi waktu kejadian menggunakan pemilih tanggal dan jam.',
            'waktu_kejadian.date' => 'Format waktu kejadian tidak valid. Pilih ulang tanggal dan jam.',
            'lokasi_url.required' => 'Isi URL lokasi insiden, diawali http:// atau https://.',
            'lokasi_url.url' => 'URL tidak valid. Contoh yang benar: https://portal.jakarta.go.id/halaman/contoh.',
            'lokasi_url.max' => 'URL terlalu panjang (maks. 255 karakter). Gunakan URL yang lebih pendek.',
            'down_time.required' => 'Isi durasi down time dalam format jam:menit, mis. 00:00 atau 02:15.',
            'down_time.date_format' => 'Format down time harus jam:menit (24 jam), mis. 00:00 atau 02:15.',
            'deskripsi.required' => 'Isi deskripsi kejadian: kronologi, dampak, dan langkah reproduksi fiktif.',
            'deskripsi.max' => 'Deskripsi terlalu panjang (maks. 5000 karakter).',
            'tindakan_teknis.required' => 'Isi tindakan teknis yang sudah dilakukan atau direkomendasikan.',
            'tindakan_teknis.max' => 'Tindakan teknis terlalu panjang (maks. 5000 karakter).',
            'bukti.max' => 'Maksimal 3 bukti URL. Hapus baris bukti yang berlebih.',
            'bukti.*.url.url' => 'URL bukti tidak valid. Awali dengan http:// atau https://.',
            'bukti.*.url.max' => 'URL bukti terlalu panjang (maks. 255 karakter).',
            'captcha_answer.required' => 'Isi jawaban verifikasi matematika.',
            'captcha_answer.integer' => 'Jawaban verifikasi harus berupa angka.',
            'syarat_ketentuan.accepted' => 'Anda harus menyetujui pernyataan kepatuhan data fiktif untuk melanjutkan.',
        ]);

        if (! MathCaptcha::verify($validated['captcha_answer'])) {
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'captcha_answer' => 'Jawaban verifikasi salah. Silakan coba lagi.',
            ]);
        }

        $buktiUrls = [];
        if (!empty($validated['bukti'])) {
            foreach ($validated['bukti'] as $row) {
                if (!empty($row['url'])) {
                    $buktiUrls[] = $row['url'];
                }
            }
        }

        try {
            $incident = SandboxIncident::create([
                'tiket_no' => SandboxIncident::generateTiketNo(),
                'kategori_insiden' => $validated['kategori_insiden'],
                'waktu_kejadian' => $validated['waktu_kejadian'],
                'lokasi_url' => $validated['lokasi_url'],
                'down_time' => $validated['down_time'],
                'deskripsi' => $validated['deskripsi'],
                'tindakan_teknis' => $validated['tindakan_teknis'],
                'bukti_urls' => $buktiUrls,
                'status' => SandboxIncident::STATUS_SUBMITTED,
            ]);
        } catch (\Exception $e) {
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'deskripsi' => 'Gagal menyimpan laporan demo ke basis data pengujian sandbox. Silakan coba lagi.',
            ]);
        }

        MathCaptcha::forget();

        return redirect()->route('bug-hunter.thank-you')->with('tiket_no', $incident->tiket_no);
    }

    public function thankYou()
    {
        $tiketNo = session('tiket_no');

        return view('bug-hunter.thank-you', compact('tiketNo'));
    }
}
