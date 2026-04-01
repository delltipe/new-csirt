<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Show paginated list of all events (upcoming first).
     * Events are ordered ascending by event_date so the nearest
     * event appears at the top. Past events are included so the
     * seeded historical data is still visible.
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')
            ->paginate(6);  // 6 per page matches original CSIRT site grid

        return view('events.index', compact('events'));
    }

    /**
     * Show a single event detail page.
     */
    public function show(Event $event)
    {
        // Fetch up to 3 other events to show in the sidebar/bottom section
        $relatedEvents = Event::where('id', '!=', $event->id)
            ->orderBy('event_date', 'desc')
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }
}