<?php
namespace App\Http\Controllers;

use App\Models\CybersecurityNews;

class NewsController extends Controller
{
    public function index() {
        // Paginate 6 items per page
        $news = CybersecurityNews::orderBy('date', 'desc')->paginate(6);
        return view('news.index', compact('news'));
    }

    public function show($id) {
        $news = CybersecurityNews::findOrFail($id);
        return view('news.show', compact('news'));
    }
}