<?php

namespace App\Http\Controllers;

use App\Models\LawRulePost;
use Illuminate\Http\Request;

class LawRulePostController extends Controller
{
    public function index()
    {
        $posts = LawRulePost::orderBy('created_at', 'desc')->paginate(12);
        return view('laws.index', compact('posts'));
    }

    public function show(LawRulePost $post)
    {
        return view('laws.show', compact('post'));
    }
}
