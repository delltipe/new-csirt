<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;

class IncidentTrackingController extends Controller
{
    /**
     * Display the public incident tracking page and search results.
     */
    public function index(Request $request)
    {
        $rawTiket = $request->query('tiket');
        $tiket = $rawTiket !== null ? strtoupper(trim((string) $rawTiket)) : null;
        $report = null;
        $error = null;

        if ($tiket !== null && $tiket !== '') {
            // Validate ticket number format (e.g. INS-2026-0001)
            if (preg_match('/^INS-\d{4}-\d{4}$/', $tiket)) {
                $report = IncidentReport::where('tiket_no', $tiket)->first();

                if (!$report) {
                    $error = 'Nomor tiket ' . $tiket . ' tidak ditemukan dalam sistem JakartaProv-CSIRT. Pastikan nomor tiket sudah benar.';
                }
            } else {
                $error = 'Format nomor tiket tidak sesuai. Contoh format tiket yang valid: INS-2026-0001.';
            }
        }

        return view('tracking.index', [
            'tiket' => $tiket,
            'report' => $report,
            'error' => $error,
        ]);
    }
}
