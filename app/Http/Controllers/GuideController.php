<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityGuide;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $guides = CybersecurityGuide::paginate(12);
        return view('guides.index', compact('guides'));
    }

    public function show(CybersecurityGuide $guide)
    {
        return view('guides.show', compact('guide'));
    }
}
