<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = CybersecurityNews::orderBy('published_at', 'desc')->paginate(12);
        return view('news.index', compact('news'));
    }

    public function show(CybersecurityNews $news)
    {
        return view('news.show', compact('news'));
    }
}
