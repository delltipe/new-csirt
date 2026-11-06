<?php

namespace App\Http\Controllers;

use App\Models\WarningPost;

class WarningPostController extends Controller
{
    public function index()
    {
        $warnings = WarningPost::orderByDesc('date')->orderByDesc('id')->paginate(12);
        return view('warnings.index', compact('warnings'));
    }

    public function show(WarningPost $warning)
    {
        return view('warnings.show', compact('warning'));
    }
}
