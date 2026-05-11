<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityGuide;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $query = CybersecurityGuide::query();

        // Search by author
        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        $guides = $query->paginate(12);
        return view('guides.index', compact('guides'));
    }

    public function show(CybersecurityGuide $guide)
    {
        return view('guides.show', compact('guide'));
    }
}
