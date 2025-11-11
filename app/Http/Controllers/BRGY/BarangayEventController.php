<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use Illuminate\Http\Request;

class BarangayEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBarangay = auth()->user()->barangays()->first();
        $events = BarangayEvent::where('barangay_id', $userBarangay->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('brgy.events.index', compact('events', 'userBarangay'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userBarangay = auth()->user()->barangays()->first();

        return view('brgy.events.create', compact('userBarangay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userBarangay = auth()->user()->barangays()->first();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['barangay_id'] = $userBarangay->id;

        BarangayEvent::create($validated);

        return redirect()
            ->route('brgy.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangayEvent $event)
    {
        $userBarangay = auth()->user()->barangays()->first();

        if ($event->barangay_id !== $userBarangay->id) {
            abort(403, 'Unauthorized');
        }

        return view('brgy.events.show', compact('event', 'userBarangay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangayEvent $event)
    {
        $userBarangay = auth()->user()->barangays()->first();

        if ($event->barangay_id !== $userBarangay->id) {
            abort(403, 'Unauthorized');
        }

        return view('brgy.events.edit', compact('event', 'userBarangay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangayEvent $event)
    {
        $userBarangay = auth()->user()->barangays()->first();

        if ($event->barangay_id !== $userBarangay->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

        return redirect()
            ->route('brgy.events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangayEvent $event)
    {
        $userBarangay = auth()->user()->barangays()->first();

        if ($event->barangay_id !== $userBarangay->id) {
            abort(403, 'Unauthorized');
        }

        $event->delete();

        return redirect()
            ->route('brgy.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
