<?php

namespace App\Http\Controllers;

use App\Models\Infographic;
use Illuminate\Http\Request;

class InfographicController extends Controller
{
    public function index()
    {
        $infographics = Infographic::paginate(12);
        return view('infographics.index', compact('infographics'));
    }

    public function show(Infographic $infographic)
    {
        return view('infographics.show', compact('infographic'));
    }
}
