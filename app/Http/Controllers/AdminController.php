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
        $laws = \App\Models\LawRulePost::orderBy('effective_date', 'desc')->get();
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
}
