<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IncidentReportController extends Controller
{
    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        // 1. Strict Validation
        $validated = $request->validate([
            'fullName'       => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phoneNumber'    => 'required|string|max:50',
            'foundDate'      => 'nullable|date',
            'domain'         => 'required|string|max:255',
            'url'            => 'required|url|max:255',
            'laporDesc'      => 'required|string',
            'riskType'       => 'nullable|string|max:255',
            'riskLevel'      => 'nullable|string|max:255',
            'cvssScore'      => 'nullable|numeric|min:0|max:10', // Added min/max
            'videoUrl'       => 'nullable|url',
            'reference'      => 'nullable|string',
            'recommendation' => 'nullable|string',
            'proofPic'       => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'captcha'        => 'required|in:JKT,jkt' // CRITICAL: Backend captcha check
        ]);

        // 2. Handle file upload safely
        $proofPath = null;
        if ($request->hasFile('proofPic')) {
            $file = $request->file('proofPic');
            $proofPath = $file->store('proof_pics', 'public');
        }

        // 3. Insert into legacy table with standard audit trails
        DB::table('lapor_insiden')->insert([
            'fullName'       => $validated['fullName'],
            'email'          => $validated['email'],
            'phoneNumber'    => $validated['phoneNumber'],
            'foundDate'      => $validated['foundDate'] ?? null,
            'domain'         => $validated['domain'],
            'url'            => $validated['url'],
            'laporDesc'      => $validated['laporDesc'],
            'riskType'       => $validated['riskType'] ?? null,
            'riskLevel'      => $validated['riskLevel'] ?? null,
            'cvssScore'      => $validated['cvssScore'] ?? null,
            'videoUrl'       => $validated['videoUrl'] ?? null,
            'reference'      => $validated['reference'] ?? null,
            'recommendation' => $validated['recommendation'] ?? null,
            'proofPic'       => $proofPath,
            'status'         => 'Menunggu Validasi', // CSIRT status
            'created_at'     => now(),               // Audit trail
            'updated_at'     => now(),               // Audit trail
        ]);

        return redirect()->route('incidents.thank-you')
            ->with('success', 'Laporan berhasil dikirim. Kami akan segera meninjaunya.');
    }

    public function thankYou()
    {
        return view('incidents.thank-you');
    }
}