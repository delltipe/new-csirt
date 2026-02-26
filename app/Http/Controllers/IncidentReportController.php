<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'required|email',
            'reporter_phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'incident_type' => 'required|string|max:100',
            'description' => 'required|string',
            'incident_date' => 'nullable|datetime',
            'affected_systems' => 'nullable|string',
            'actions_taken' => 'nullable|string',
            'severity' => 'nullable|string|in:critical,high,medium,low',
        ]);

        IncidentReport::create($validated);

        return redirect()->route('incidents.thank-you')
            ->with('success', 'Incident report submitted successfully. We will review it shortly.');
    }

    public function thankYou()
    {
        return view('incidents.thank-you');
    }
}
