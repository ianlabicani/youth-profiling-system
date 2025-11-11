<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display all events for public viewing with filters
     */
    public function index(Request $request)
    {
        $query = BarangayEvent::with('skCouncil', 'barangay');

        // Search filter - by title, description, or organizer
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        // Date filter - from date
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        // Date filter - to date
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        // Status filter - upcoming, today, or past (default: upcoming and today)
        $today = now()->toDateString();
        $status = $request->input('status', '');

        if ($status === 'past') {
            // Show only past events
            $query->whereDate('date', '<', $today);
        } elseif ($status === 'upcoming') {
            // Show only upcoming events
            $query->whereDate('date', '>', $today);
        } elseif ($status === 'today') {
            // Show only today's events
            $query->whereDate('date', '=', $today);
        } else {
            // Default: show upcoming and today's events
            $query->whereDate('date', '>=', $today);
        }

        // Barangay filter - filter by barangay_id directly
        if ($request->filled('organizer_id')) {
            $query->where('barangay_id', $request->input('organizer_id'));
        }

        // Order by date and time and paginate
        $events = $query->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(12);

        // Get all barangays for barangay filter (regardless of SK Council status)
        $barangays = \App\Models\Barangay::orderBy('name', 'asc')->get();

        return view('public.events', compact('events', 'barangays'));
    }

    /**
     * Get featured events for welcome page (3 upcoming, 3 today, 3 past)
     */
    public function featured()
    {
        $today = now()->toDateString();

        // Get upcoming events (max 3)
        $upcomingEvents = BarangayEvent::with('skCouncil')
            ->whereDate('date', '>', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->limit(3)
            ->get();

        // Get today's events (max 3)
        $todayEvents = BarangayEvent::with('skCouncil')
            ->whereDate('date', '=', $today)
            ->orderBy('time', 'asc')
            ->limit(3)
            ->get();

        // Get past events (max 3, ordered by most recent first)
        $pastEvents = BarangayEvent::with('skCouncil')
            ->whereDate('date', '<', $today)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->limit(3)
            ->get();

        return compact('upcomingEvents', 'todayEvents', 'pastEvents');
    }

    /**
     * Display a single event with full details
     */
    public function show(BarangayEvent $event)
    {
        $event->load('skCouncil', 'barangay');

        return view('public.events.show', compact('event'));
    }
}
