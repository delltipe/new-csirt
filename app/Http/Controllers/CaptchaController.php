<?php

namespace App\Http\Controllers;

use App\Support\MathCaptcha;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    /**
     * Refresh captcha and return new question as JSON.
     * Used by both incident and contact forms via fetch.
     */
    public function refresh(Request $request)
    {
        $question = MathCaptcha::regenerate();

        return response()->json([
            'question' => $question,
        ]);
    }
}
