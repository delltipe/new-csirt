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
        // Validate according to legacy lapor_insiden columns
        $validated = $request->validate([
            'fullName' => 'required|string|max:65535',
            'email' => 'required|email|max:65535',
            'phoneNumber' => 'required|string|max:50',
            'foundDate' => 'nullable|date',
            'domain' => 'required|string|max:65535',
            'url' => 'required|url|max:65535',
            'laporDesc' => 'required|string',
            'riskType' => 'nullable|string|max:255',
            'riskLevel' => 'nullable|string|max:255',
            'cvssScore' => 'nullable|numeric',
            'videoUrl' => 'nullable|url',
            'reference' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'proofPic' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        // handle file upload
        $proofPath = null;
        if ($request->hasFile('proofPic')) {
            $file = $request->file('proofPic');
            $proofPath = $file->store('proof_pics', 'public');
        }

        // Insert into legacy table lapor_insiden
        DB::table('lapor_insiden')->insert([
            'fullName' => $validated['fullName'],
            'email' => $validated['email'],
            'phoneNumber' => $validated['phoneNumber'],
            'foundDate' => $validated['foundDate'] ?? null,
            'domain' => $validated['domain'],
            'url' => $validated['url'],
            'laporDesc' => $validated['laporDesc'],
            'riskType' => $validated['riskType'] ?? null,
            'riskLevel' => $validated['riskLevel'] ?? null,
            'cvssScore' => $validated['cvssScore'] ?? null,
            'videoUrl' => $validated['videoUrl'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'recommendation' => $validated['recommendation'] ?? null,
            'proofPic' => $proofPath,
        ]);

        return redirect()->route('incidents.thank-you')
            ->with('success', 'Incident report submitted successfully. We will review it shortly.');
    }

    public function thankYou()
    {
        return view('incidents.thank-you');
    }
}
