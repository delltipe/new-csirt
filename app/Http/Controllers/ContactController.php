<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'inquiry_type' => 'required|string|in:general,support,partnership,media,other',
        ]);

        try {
            ContactMessage::create($validated);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'message' => 'Gagal mengirim pesan. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('contact.thank-you')
            ->with('success', 'Pesan berhasil dikirim. Kami akan segera merespons.');
    }

    public function thankYou()
    {
        return view('contact.thank-you');
    }
}
