<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use App\Models\WarningPost;
use App\Models\Event;
use App\Models\Infographic;
use App\Models\LawRulePost;
use App\Models\CybersecurityGuide;

class SearchController extends Controller
{
    public function index()
    {
        $query = request('q', '');
        $results = [];

        if (strlen(trim($query)) < 2) {
            return view('search.index', compact('query', 'results'));
        }

        $term = '%' . trim($query) . '%';

        $results['news'] = CybersecurityNews::where('title', 'LIKE', $term)
            ->orWhere('description', 'LIKE', $term)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $results['warnings'] = WarningPost::where('title', 'LIKE', $term)
            ->orWhere('description', 'LIKE', $term)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $results['events'] = Event::where('title', 'LIKE', $term)
            ->orWhere('description', 'LIKE', $term)
            ->orderBy('event_date', 'desc')
            ->limit(10)
            ->get();

        $results['infographics'] = Infographic::where('title', 'LIKE', $term)
            ->limit(10)
            ->get();

        $results['laws'] = LawRulePost::where('title', 'LIKE', $term)
            ->orWhere('description', 'LIKE', $term)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $results['guides'] = CybersecurityGuide::where('title', 'LIKE', $term)
            ->orWhere('author', 'LIKE', $term)
            ->limit(10)
            ->get();

        return view('search.index', compact('query', 'results'));
    }
}
