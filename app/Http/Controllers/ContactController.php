<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Support\MathCaptcha;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        $captchaQuestion = MathCaptcha::question();

        return view('contact.create', compact('captchaQuestion'));
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
            'captcha_answer' => 'required|integer',
        ]);

        if (! MathCaptcha::verify($validated['captcha_answer'])) {
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'captcha_answer' => 'Jawaban verifikasi salah. Silakan coba lagi.',
            ]);
        }

        // Remove captcha from data before creating record
        unset($validated['captcha_answer']);

        try {
            ContactMessage::create($validated);
        } catch (\Exception $e) {
            MathCaptcha::regenerate();
            return back()->withInput()->withErrors([
                'message' => 'Gagal mengirim pesan. Silakan coba lagi.',
            ]);
        }

        MathCaptcha::forget();

        return redirect()->route('contact.thank-you')
            ->with('success', 'Pesan berhasil dikirim. Kami akan segera merespons.');
    }

    public function thankYou()
    {
        return view('contact.thank-you');
    }
}
