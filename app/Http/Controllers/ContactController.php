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

        ContactMessage::create($validated);

        return redirect()->route('contact.thank-you')
            ->with('success', 'Your message has been sent. We will get back to you soon.');
    }

    public function thankYou()
    {
        return view('contact.thank-you');
    }
}
