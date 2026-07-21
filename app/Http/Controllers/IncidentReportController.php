<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentReportController extends Controller
{
    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request) 
    {
        // 1. Validate EVERYTHING at once because it's all in this request now
        $validated = $request->validate([
            // Data from Step 1
            'fullName'       => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phoneNumber'    => 'required|string|max:50',
            'foundDate'      => 'nullable|date',

            // Data from Step 2
            'domain'         => 'required|string|max:255',
            'url'            => 'required|url|max:255',

            // Data from Step 3
            'laporDesc'      => 'required|string',
            'riskType'       => 'nullable|string|max:255',
            'riskLevel'      => 'nullable|string|max:255',
            'cvssScore'      => 'nullable|numeric|min:0|max:10',
            'videoUrl'       => 'nullable|url|max:255',
            'reference'      => 'nullable|string|max:255',
            'recommendation' => 'nullable|string|max:255',
            'proofPic'       => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'captcha'        => 'required|in:JKT,jkt',
        ]);

        // 2. Handle the file upload
        $proofPath = null;
        if ($request->hasFile('proofPic')) {
            $proofPath = $request->file('proofPic')->store('proof_pics', 'public');
        }

        // 3. Insert directly into the database
        try {
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
                'status'         => 'Menunggu Validasi',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'laporDesc' => 'Gagal menyimpan laporan. Silakan coba lagi atau hubungi CSIRT langsung.',
            ]);
        }

        // 4. Clean up
        return redirect()->route('incidents.thank-you')->with('success', 'Laporan berhasil dikirim!');
    }
    
    public function thankYou() {
        return view('incidents.thank-you');
    }
}