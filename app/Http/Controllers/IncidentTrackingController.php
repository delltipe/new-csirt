<?php

namespace App\Http\Controllers;

use App\Models\SandboxIncident;
use Illuminate\Http\Request;

class IncidentTrackingController extends Controller
{
    /**
     * Display the public incident tracking page and search results for sandbox demo incidents.
     */
    public function index(Request $request)
    {
        $rawTiket = $request->query('tiket');
        $tiket = $rawTiket !== null ? strtoupper(trim((string) $rawTiket)) : null;
        $report = null;
        $error = null;

        if ($tiket !== null && $tiket !== '') {
            // Validate demo ticket number format: DEMO-YYYY-XXXXXXXX (8 alphanumeric characters)
            if (preg_match('/^DEMO-\d{4}-[A-Z0-9]{8}$/i', $tiket)) {
                $report = SandboxIncident::where('tiket_no', $tiket)->first();

                if (!$report) {
                    $error = 'Nomor tiket demo ' . $tiket . ' tidak ditemukan dalam basis data sandbox JakartaProv-CSIRT. Pastikan nomor tiket sudah benar.';
                }
            } else {
                $error = 'Format nomor tiket simulasi tidak sesuai. Contoh format tiket demo yang valid: DEMO-2026-B7TGZTCI.';
            }
        }

        return view('tracking.index', [
            'tiket' => $tiket,
            'report' => $report,
            'error' => $error,
        ]);
    }
}
