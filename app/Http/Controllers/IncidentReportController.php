<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IncidentReportController extends Controller
{
    // Step 1: Data Pelapor
    public function createStep1() {
        $incident = Session::get('incident');
        return view('incidents.step1', compact('incident'));
    }

    public function postStep1(Request $request) {
        $validated = $request->validate([
            'fullName'    => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phoneNumber' => 'required|string|max:50',
            'foundDate'   => 'nullable|date',
        ]);
        Session::put('incident', $validated);
        return redirect()->route('incidents.create.step2');
    }

    // Step 2: Data Website
    public function createStep2() {
        if (!Session::has('incident')) return redirect()->route('incidents.create.step1');
        $incident = Session::get('incident');
        return view('incidents.step2', compact('incident'));
    }

    public function postStep2(Request $request) {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'url'    => 'required|url|max:255',
        ]);
        $currentData = Session::get('incident');
        Session::put('incident', array_merge($currentData, $validated));
        return redirect()->route('incidents.create.step3');
    }

    // Step 3: Detail Laporan
    public function createStep3() {
        if (!Session::has('incident')) return redirect()->route('incidents.create.step1');
        return view('incidents.step3');
    }

    public function store(Request $request) {
        $step1and2 = Session::get('incident');

        $validated = $request->validate([
            'laporDesc'      => 'required|string',
            'riskType'       => 'nullable|string|max:255',
            'riskLevel'      => 'nullable|string|max:255',
            'cvssScore'      => 'nullable|numeric|min:0|max:10',
            'videoUrl'       => 'nullable|url',
            'reference'      => 'nullable|string',
            'recommendation' => 'nullable|string',
            'proofPic'       => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'captcha'        => 'required|in:JKT,jkt'
        ]);

        $proofPath = null;
        if ($request->hasFile('proofPic')) {
            $proofPath = $request->file('proofPic')->store('proof_pics', 'public');
        }

        DB::table('lapor_insiden')->insert(array_merge($step1and2, [
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
        ]));

        Session::forget('incident');
        return redirect()->route('incidents.thank-you')->with('success', 'Laporan berhasil dikirim!');
    }

    public function thankYou() {
        return view('incidents.thank-you');
    }
}