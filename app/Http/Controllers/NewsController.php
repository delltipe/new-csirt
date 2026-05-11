<?php
namespace App\Http\Controllers;

use App\Models\CybersecurityNews;

class NewsController extends Controller
{
    public function index() {
        // Paginate 6 items per page
        $news = CybersecurityNews::orderBy('date', 'desc')->paginate(6);

        // Get available years for the filter (SQLite compatible)
        $availableYears = CybersecurityNews::selectRaw("DISTINCT strftime('%Y', date) as year")
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();

        return view('news.index', compact('news', 'availableYears'));
    }

    public function show($id) {
        $news = CybersecurityNews::findOrFail($id);
        return view('news.show', compact('news'));
    }
}