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
            ->get()
            ->map(function ($item) {
                $opt = public_path('images/berita-optimized/' . $item->id . '.webp');
                $item->optimized_thumbnail = file_exists($opt)
                    ? asset('images/berita-optimized/' . $item->id . '.webp')
                    : $item->thumbnail;
                return $item;
            });

        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(4)
            ->get()
            ->map(function ($item) {
                $opt = public_path('images/gallery-optimized/' . $item->id . '.webp');
                $item->optimized_thumbnail = file_exists($opt)
                    ? asset('images/gallery-optimized/' . $item->id . '.webp')
                    : ($item->thumbnail ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80');
                return $item;
            });

        $slides = HeroSlide::active()->ordered()->get()
            ->map(function ($slide) {
                $opt = public_path('images/hero-optimized/' . $slide->id . '.webp');
                $slide->optimized_gambar = file_exists($opt)
                    ? asset('images/hero-optimized/' . $slide->id . '.webp')
                    : $slide->gambar;
                return $slide;
            });

        return view('home', [
            'recentNews' => $recentNews,
            'upcomingEvents' => $upcomingEvents,
            'slides' => $slides,
        ]);
    }
}
