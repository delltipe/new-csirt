<?php

namespace App\Http\Controllers;

use App\Models\LampiranInsiden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function download(Request $request, LampiranInsiden $attachment)
    {
        $report = $attachment->report;
        if (! $report || (! $request->user()->is_admin && $report->user_id !== $request->user()->id)
            || $attachment->jenis !== 'file' || ! preg_match('~\Abukti_laporan/[A-Za-z0-9_-]+\.[A-Za-z0-9]+\z~', $attachment->value)) {
            return response()->view('errors.evidence-unavailable', [], 404);
        }

        // Legacy files remain accessible to their owner while a verified migration is prepared.
        // Their old public URLs are still public until the web server blocks that directory.
        $disk = Storage::disk('evidence');
        if (! $disk->exists($attachment->value)) {
            $disk = Storage::disk('public');
        }
        if (! $disk->exists($attachment->value)) {
            return response()->view('errors.evidence-unavailable', [], 404);
        }

        return $disk->download($attachment->value, basename($attachment->value), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
