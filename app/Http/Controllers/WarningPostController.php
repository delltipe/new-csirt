<?php

namespace App\Http\Controllers;

use App\Models\WarningPost;
use Illuminate\Http\Request;

class WarningPostController extends Controller
{
    public function index()
    {
        $warnings = WarningPost::orderBy('issued_at', 'desc')->paginate(12);
        return view('warnings.index', compact('warnings'));
    }

    public function show(WarningPost $warning)
    {
        return view('warnings.show', compact('warning'));
    }
}
