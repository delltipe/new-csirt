<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\LampiranInsiden;
use App\Models\TacAgreement;
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
        return view('bug-hunter.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_insiden' => 'required|string|max:255',
            'waktu_kejadian' => 'required|date',
            'lokasi_url' => 'required|url|max:255',
            'down_time' => 'required|date_format:H:i',
            'deskripsi' => 'required|string',
            'tindakan_teknis' => 'required|string',
            'bukti' => 'nullable|array|max:3',
            'bukti.*.jenis' => 'nullable|in:file,url',
            'bukti.*.file' => 'nullable|file|mimes:png,jpg,jpeg,gif,pdf|max:5120',
            'bukti.*.url' => 'nullable|url|max:255',
        ]);

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
            return back()->withInput()->withErrors([
                'deskripsi' => 'Gagal menyimpan laporan. Silakan coba lagi atau hubungi CSIRT langsung.',
            ]);
        }

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
