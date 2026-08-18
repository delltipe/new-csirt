<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CybersecurityNews;
use App\Models\IncidentReport;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Show admin login form
    public function showLogin()
    {
        return view('admin.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Only allow users with 'is_admin' flag (add this to your users table if not present)
            if (Auth::user() && Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized.']);
            }
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Show admin dashboard
    public function dashboard()
    {
        $news = \App\Models\CybersecurityNews::orderBy('date', 'desc')->paginate(15);
        $events = \App\Models\Event::orderBy('event_date', 'desc')->paginate(15);
        $infographics = \App\Models\Infographic::orderByDesc('id')->paginate(15);
        $warnings = \App\Models\WarningPost::orderBy('date', 'desc')->paginate(15);
        $laws = \App\Models\LawRulePost::orderBy('date', 'desc')->paginate(15);
        $guides = \App\Models\CybersecurityGuide::orderByDesc('id')->paginate(15);
        $incidents = IncidentReport::orderByDesc('created_at')->paginate(15);
        $pendingIncidents = IncidentReport::where('status', IncidentReport::STATUS_PENDING)->count();
        return view('admin.dashboard', compact('news', 'events', 'infographics', 'warnings', 'laws', 'guides', 'incidents', 'pendingIncidents'));
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    // NEWS CRUD for admin
    public function newsList() {
        $news = \App\Models\CybersecurityNews::orderBy('date', 'desc')->get();
        return view('admin.partials.news', compact('news'));
    }

    public function newsStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        try {
            \App\Models\CybersecurityNews::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan berita. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'News added!');
    }

    public function newsEdit($id) {
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        return view('admin.news_edit', compact('newsItem'));
    }

    public function newsUpdate(Request $request, $id) {
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        try {
            $newsItem->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui berita. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'News updated!');
    }

    public function newsDelete($id) {
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        try {
            $newsItem->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus berita. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'News deleted!');
    }

    // ============================================
    // EVENTS CRUD
    // ============================================
    public function eventsList() {
        $events = \App\Models\Event::orderBy('event_date', 'desc')->get();
        return view('admin.partials.events', compact('events'));
    }

    public function eventStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'event_type' => 'nullable|string',
            'registration_url' => 'nullable|string',
            'capacity' => 'nullable|integer',
        ]);
        try {
            \App\Models\Event::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan event. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Event added!');
    }

    public function eventEdit($id) {
        $event = \App\Models\Event::findOrFail($id);
        return view('admin.event_edit', compact('event'));
    }

    public function eventUpdate(Request $request, $id) {
        $event = \App\Models\Event::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'event_type' => 'nullable|string',
            'registration_url' => 'nullable|string',
            'capacity' => 'nullable|integer',
        ]);
        try {
            $event->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui event. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Event updated!');
    }

    public function eventDelete($id) {
        $event = \App\Models\Event::findOrFail($id);
        try {
            $event->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus event. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Event deleted!');
    }

    // ============================================
    // WARNINGS CRUD
    // ============================================
    public function warningsList() {
        $warnings = \App\Models\WarningPost::orderBy('date', 'desc')->get();
        return view('admin.partials.warnings', compact('warnings'));
    }

    public function warningStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        try {
            \App\Models\WarningPost::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan peringatan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Warning added!');
    }

    public function warningEdit($id) {
        $warning = \App\Models\WarningPost::findOrFail($id);
        return view('admin.warning_edit', compact('warning'));
    }

    public function warningUpdate(Request $request, $id) {
        $warning = \App\Models\WarningPost::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        try {
            $warning->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui peringatan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Warning updated!');
    }

    public function warningDelete($id) {
        $warning = \App\Models\WarningPost::findOrFail($id);
        try {
            $warning->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus peringatan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Warning deleted!');
    }

    // ============================================
    // LAWS CRUD
    // ============================================
    public function lawsList() {
        $laws = \App\Models\LawRulePost::orderBy('date', 'desc')->get();
        return view('admin.partials.laws', compact('laws'));
    }

    public function lawStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'downloadAmount' => 'nullable|integer',
        ]);
        try {
            \App\Models\LawRulePost::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan peraturan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Law added!');
    }

    public function lawEdit($id) {
        $law = \App\Models\LawRulePost::findOrFail($id);
        return view('admin.law_edit', compact('law'));
    }

    public function lawUpdate(Request $request, $id) {
        $law = \App\Models\LawRulePost::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'downloadAmount' => 'nullable|integer',
        ]);
        try {
            $law->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui peraturan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Law updated!');
    }

    public function lawDelete($id) {
        $law = \App\Models\LawRulePost::findOrFail($id);
        try {
            $law->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus peraturan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Law deleted!');
    }

    // ============================================
    // GUIDES CRUD
    // ============================================
    public function guidesList() {
        $guides = \App\Models\CybersecurityGuide::all();
        return view('admin.partials.guides', compact('guides'));
    }

    public function guideStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'link' => 'required|string',
        ]);
        try {
            \App\Models\CybersecurityGuide::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan panduan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Guide added!');
    }

    public function guideEdit($id) {
        $guide = \App\Models\CybersecurityGuide::findOrFail($id);
        return view('admin.guide_edit', compact('guide'));
    }

    public function guideUpdate(Request $request, $id) {
        $guide = \App\Models\CybersecurityGuide::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'link' => 'required|string',
        ]);
        try {
            $guide->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui panduan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Guide updated!');
    }

    public function guideDelete($id) {
        $guide = \App\Models\CybersecurityGuide::findOrFail($id);
        try {
            $guide->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus panduan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Guide deleted!');
    }

    // ============================================
    // INFOGRAPHICS CRUD
    // ============================================
    public function infographicsList() {
        $infographics = \App\Models\Infographic::all();
        return view('admin.partials.infographics', compact('infographics'));
    }

    public function infographicStore(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
        ]);
        try {
            \App\Models\Infographic::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan infografis. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Infographic added!');
    }

    public function infographicEdit($id) {
        $infographic = \App\Models\Infographic::findOrFail($id);
        return view('admin.infographic_edit', compact('infographic'));
    }

    public function infographicUpdate(Request $request, $id) {
        $infographic = \App\Models\Infographic::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
        ]);
        try {
            $infographic->update($data);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui infografis. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Infographic updated!');
    }

    public function infographicDelete($id) {
        $infographic = \App\Models\Infographic::findOrFail($id);
        try {
            $infographic->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menghapus infografis. Silakan coba lagi.']);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Infographic deleted!');
    }

    // ============================================
    // INCIDENT REVIEW
    // ============================================
    public function incidentsList(Request $request) {
        $incidents = IncidentReport::with('user')
            ->orderByDesc('created_at')
            ->when($request->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(15);
        return view('admin.incidents.index', compact('incidents'));
    }

    public function incidentShow($id) {
        $incident = IncidentReport::with(['attachments', 'user'])->findOrFail($id);
        return view('admin.incidents.show', compact('incident'));
    }

    public function incidentReview(Request $request, $id) {
        $incident = IncidentReport::findOrFail($id);

        $validated = $request->validate([
            'cwe' => 'nullable|string|max:255',
            'severity' => 'nullable|string|in:Low,Medium,High,Critical',
            'status' => 'required|string',
        ]);

        $newStatus = $validated['status'];

        if ($newStatus !== $incident->status && !$incident->canTransitionTo($newStatus)) {
            return back()->withErrors(['status' => 'Transisi status tidak valid untuk status saat ini.']);
        }

        try {
            $incident->update([
                'cwe' => $validated['cwe'] ?? $incident->cwe,
                'severity' => $validated['severity'] ?? $incident->severity,
                'status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['status' => 'Gagal memperbarui laporan. Silakan coba lagi.']);
        }

        return back()->with('success', 'Laporan insiden berhasil diperbarui.');
    }

    public function incidentDelete($id) {
        $incident = IncidentReport::findOrFail($id);
        try {
            $incident->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['status' => 'Gagal menghapus laporan. Silakan coba lagi.']);
        }
        return redirect()->route('admin.incidents.list')->with('success', 'Laporan insiden berhasil dihapus.');
    }
}
