<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CybersecurityNews;
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
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $news = \App\Models\CybersecurityNews::orderBy('date', 'desc')->get();
        $events = \App\Models\Event::orderBy('event_date', 'desc')->get();
        $infographics = \App\Models\Infographic::orderByDesc('id')->get();
        $warnings = \App\Models\WarningPost::orderBy('date', 'desc')->get();
        $laws = \App\Models\LawRulePost::orderBy('date', 'desc')->get();
        $guides = \App\Models\CybersecurityGuide::orderByDesc('id')->get();
        return view('admin.dashboard', compact('news', 'events', 'infographics', 'warnings', 'laws', 'guides'));
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    // NEWS CRUD for admin
    public function newsList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $news = \App\Models\CybersecurityNews::orderBy('date', 'desc')->get();
        return view('admin.partials.news', compact('news'));
    }

    public function newsStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        \App\Models\CybersecurityNews::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'News added!');
    }

    public function newsEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        return view('admin.news_edit', compact('newsItem'));
    }

    public function newsUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
        ]);
        $newsItem->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'News updated!');
    }

    public function newsDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $newsItem = \App\Models\CybersecurityNews::findOrFail($id);
        $newsItem->delete();
        return redirect()->route('admin.dashboard')->with('success', 'News deleted!');
    }

    // ============================================
    // EVENTS CRUD
    // ============================================
    public function eventsList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $events = \App\Models\Event::orderBy('event_date', 'desc')->get();
        return view('admin.partials.events', compact('events'));
    }

    public function eventStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
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
        \App\Models\Event::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Event added!');
    }

    public function eventEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $event = \App\Models\Event::findOrFail($id);
        return view('admin.event_edit', compact('event'));
    }

    public function eventUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
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
        $event->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Event updated!');
    }

    public function eventDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $event = \App\Models\Event::findOrFail($id);
        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event deleted!');
    }

    // ============================================
    // WARNINGS CRUD
    // ============================================
    public function warningsList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $warnings = \App\Models\WarningPost::orderBy('date', 'desc')->get();
        return view('admin.partials.warnings', compact('warnings'));
    }

    public function warningStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
            'file' => 'nullable|file|image|max:2048',
        ]);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('warnings', 'public');
        }
        \App\Models\WarningPost::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Warning added!');
    }

    public function warningEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $warning = \App\Models\WarningPost::findOrFail($id);
        return view('admin.warning_edit', compact('warning'));
    }

    public function warningUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $warning = \App\Models\WarningPost::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'source' => 'nullable|string',
            'date' => 'required|date',
            'file' => 'nullable|file|image|max:2048',
        ]);
        if ($request->hasFile('file')) {
            if ($warning->file_path) {
                \Storage::disk('public')->delete($warning->file_path);
            }
            $data['file_path'] = $request->file('file')->store('warnings', 'public');
        }
        $warning->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Warning updated!');
    }

    public function warningDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $warning = \App\Models\WarningPost::findOrFail($id);
        if ($warning->file_path) {
            \Storage::disk('public')->delete($warning->file_path);
        }
        $warning->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Warning deleted!');
    }

    // ============================================
    // LAWS CRUD
    // ============================================
    public function lawsList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $laws = \App\Models\LawRulePost::orderBy('date', 'desc')->get();
        return view('admin.partials.laws', compact('laws'));
    }

    public function lawStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'downloadAmount' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf|max:4096',
        ]);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('laws', 'public');
        }
        \App\Models\LawRulePost::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Law added!');
    }

    public function lawEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $law = \App\Models\LawRulePost::findOrFail($id);
        return view('admin.law_edit', compact('law'));
    }

    public function lawUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $law = \App\Models\LawRulePost::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'downloadAmount' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf|max:4096',
        ]);
        if ($request->hasFile('file')) {
            if ($law->file_path) {
                \Storage::disk('public')->delete($law->file_path);
            }
            $data['file_path'] = $request->file('file')->store('laws', 'public');
        }
        $law->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Law updated!');
    }

    public function lawDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $law = \App\Models\LawRulePost::findOrFail($id);
        if ($law->file_path) {
            \Storage::disk('public')->delete($law->file_path);
        }
        $law->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Law deleted!');
    }

    // ============================================
    // GUIDES CRUD
    // ============================================
    public function guidesList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $guides = \App\Models\Guide::all();
        return view('admin.partials.guides', compact('guides'));
    }

    public function guideStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'link' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:4096',
        ]);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('guides', 'public');
        }
        \App\Models\Guide::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Guide added!');
    }

    public function guideEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $guide = \App\Models\Guide::findOrFail($id);
        return view('admin.guide_edit', compact('guide'));
    }

    public function guideUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $guide = \App\Models\Guide::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'link' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:4096',
        ]);
        if ($request->hasFile('file')) {
            if ($guide->file_path) {
                \Storage::disk('public')->delete($guide->file_path);
            }
            $data['file_path'] = $request->file('file')->store('guides', 'public');
        }
        $guide->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Guide updated!');
    }

    public function guideDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $guide = \App\Models\Guide::findOrFail($id);
        if ($guide->file_path) {
            \Storage::disk('public')->delete($guide->file_path);
        }
        $guide->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Guide deleted!');
    }

    // ============================================
    // INFOGRAPHICS CRUD
    // ============================================
    public function infographicsList() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $infographics = \App\Models\Infographic::all();
        return view('admin.partials.infographics', compact('infographics'));
    }

    public function infographicStore(Request $request) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
        ]);
        \App\Models\Infographic::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Infographic added!');
    }

    public function infographicEdit($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $infographic = \App\Models\Infographic::findOrFail($id);
        return view('admin.infographic_edit', compact('infographic'));
    }

    public function infographicUpdate(Request $request, $id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $infographic = \App\Models\Infographic::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string',
            'thumbnail' => 'required|string',
        ]);
        $infographic->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Infographic updated!');
    }

    public function infographicDelete($id) {
        if (!auth()->user() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login');
        }
        $infographic = \App\Models\Infographic::findOrFail($id);
        $infographic->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Infographic deleted!');
    }
}
