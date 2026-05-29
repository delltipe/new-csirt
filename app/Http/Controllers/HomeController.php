<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with recent news and events
     */
    public function index(): View
    {
        $recentNews = CybersecurityNews::orderBy('date', 'desc')
            ->limit(3)
            ->get();

        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(4)
            ->get();

        return view('home', [
            'recentNews' => $recentNews,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
