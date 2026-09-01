<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use App\Models\Event;
use App\Models\HeroSlide;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with recent news and events
     */
    public function index(): View
    {
        $recentNews = CybersecurityNews::orderBy('date', 'desc')
            ->limit(6)
            ->get();

        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(4)
            ->get();

        $slides = HeroSlide::active()->ordered()->get();

        return view('home', [
            'recentNews' => $recentNews,
            'upcomingEvents' => $upcomingEvents,
            'slides' => $slides,
        ]);
    }
}
