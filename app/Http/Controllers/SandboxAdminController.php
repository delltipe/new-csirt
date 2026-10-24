<?php

namespace App\Http\Controllers;

use App\Models\SandboxIncident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SandboxAdminController extends Controller
{
    /**
     * Show the sandbox administrator login form.
     */
    public function showLoginForm(Request $request)
    {
        if ($request->session()->get('sandbox_admin_authenticated')) {
            return redirect()->route('sandbox-admin.incidents.index');
        }

        return view('sandbox-admin.login');
    }

    /**
     * Handle a sandbox administrator login attempt.
     */
    public function login(Request $request)
    {
        $configUsername = config('services.sandbox_admin.username');
        $configPasswordHash = config('services.sandbox_admin.password_hash');

        // Fail closed if required credentials are absent, empty, or whitespace-only
        if (
            empty($configUsername) ||
            empty($configPasswordHash) ||
            ! is_string($configUsername) ||
            ! is_string($configPasswordHash) ||
            trim($configUsername) === '' ||
            trim($configPasswordHash) === ''
        ) {
            return back()->withInput($request->except('password'))->withErrors([
                'username' => 'Autentikasi administrator sandbox dinonaktifkan atau belum dikonfigurasi.',
            ]);
        }

        // Verify that the configured password hash is a recognized cryptographic hash algorithm
        $hashInfo = @password_get_info($configPasswordHash);
        if (empty($hashInfo['algo'])) {
            return back()->withInput($request->except('password'))->withErrors([
                'username' => 'Autentikasi administrator sandbox dinonaktifkan atau belum dikonfigurasi.',
            ]);
        }

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Nama pengguna administrator wajib diisi.',
            'password.required' => 'Kata sandi administrator wajib diisi.',
        ]);

        $usernameMatches = hash_equals($configUsername, $credentials['username']);
        $passwordMatches = false;

        try {
            $passwordMatches = Hash::check($credentials['password'], $configPasswordHash);
        } catch (\Throwable $e) {
            $passwordMatches = false;
        }

        if (! $usernameMatches || ! $passwordMatches) {
            return back()->withInput($request->except('password'))->withErrors([
                'username' => 'Nama pengguna atau kata sandi tidak valid.',
            ]);
        }

        // Protect against session fixation
        $request->session()->regenerate();
        $request->session()->put('sandbox_admin_authenticated', true);

        return redirect()->intended(route('sandbox-admin.incidents.index'));
    }

    /**
     * Log the administrator out, invalidating session and regenerating CSRF token.
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sandbox-admin.login');
    }

    /**
     * Display a listing of fictional sandbox incidents.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $query = SandboxIncident::query()->orderBy('created_at', 'desc');

        if ($statusFilter && array_key_exists($statusFilter, SandboxIncident::labels())) {
            $query->where('status', $statusFilter);
        }

        $incidents = $query->paginate(15)->withQueryString();

        $statusCounts = SandboxIncident::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalCount = SandboxIncident::count();

        return view('sandbox-admin.incidents.index', compact('incidents', 'statusFilter', 'statusCounts', 'totalCount'));
    }

    /**
     * Display the specified sandbox incident.
     */
    public function show(int $id)
    {
        $incident = SandboxIncident::findOrFail($id);

        $transitionTargets = SandboxIncident::transitions()[$incident->status] ?? [];
        $statusOptions = array_intersect_key(
            SandboxIncident::labels(),
            array_flip($transitionTargets)
        );

        return view('sandbox-admin.incidents.show', compact('incident', 'transitionTargets', 'statusOptions'));
    }

    /**
     * Update only the permitted status field of the specified sandbox incident.
     */
    public function updateStatus(Request $request, int $id)
    {
        $incident = SandboxIncident::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(SandboxIncident::labels()))],
        ], [
            'status.required' => 'Pilih status baru yang ingin diterapkan.',
            'status.in' => 'Nilai status yang dipilih tidak valid.',
        ]);

        $newStatus = $validated['status'];

        if (! $incident->canTransitionTo($newStatus)) {
            $currentLabel = $incident->statusLabel();
            $targetLabel = SandboxIncident::labels()[$newStatus] ?? $newStatus;

            return back()->withInput()->withErrors([
                'status' => "Perubahan status dari '{$currentLabel}' ke '{$targetLabel}' tidak diizinkan.",
            ]);
        }

        // Strictly update only the status attribute; prevent mass assignment or altering any other field
        $incident->status = $newStatus;
        $incident->save();

        return redirect()->route('sandbox-admin.incidents.show', $incident->id)
            ->with('success', "Status laporan {$incident->tiket_no} berhasil diperbarui menjadi '{$incident->statusLabel()}'.");
    }
}
