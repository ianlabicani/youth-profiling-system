<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\BarangayEvent;
use App\Models\SKCouncil;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarangayEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBarangay = auth()->user()->barangays()->first();
        $events = BarangayEvent::where('barangay_id', $userBarangay->id)
            ->with('skCouncil')
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
        $skCouncils = SKCouncil::where('barangay_id', $userBarangay->id)->get();

        return view('brgy.events.create', compact('userBarangay', 'skCouncils'));
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
            'time' => 'required|string', // parsed below to accept multiple formats
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sk_council_id' => 'nullable|exists:sk_councils,id',
        ]);

        // Normalize time input to H:i:s (DB time column)
        $timeInput = $validated['time'] ?? null;
        $time = null;
        if ($timeInput) {
            $formats = ['H:i', 'H:i:s', 'g:i A', 'h:i A', 'g:i a', 'h:i a'];
            foreach ($formats as $fmt) {
                try {
                    $time = Carbon::createFromFormat($fmt, $timeInput);
                    break;
                } catch (\Exception $e) {
                    // try next
                }
            }

            // As a fallback try flexible parse
            if (!$time) {
                try {
                    $time = Carbon::parse($timeInput);
                } catch (\Exception $e) {
                    // invalid time
                }
            }

            if (!$time) {
                return back()->withErrors(['time' => 'The time field must be a valid time (e.g., 08:00 or 08:00 AM)'])->withInput();
            }

            // store as H:i:s
            $validated['time'] = $time->format('H:i:s');
        }

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

        $skCouncils = SKCouncil::where('barangay_id', $userBarangay->id)->get();

        return view('brgy.events.edit', compact('event', 'userBarangay', 'skCouncils'));
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
            'time' => 'required|string', // parsed below to accept multiple formats
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sk_council_id' => 'nullable|exists:sk_councils,id',
        ]);

        // Normalize time input to H:i:s (DB time column)
        $timeInput = $validated['time'] ?? null;
        $time = null;
        if ($timeInput) {
            $formats = ['H:i', 'H:i:s', 'g:i A', 'h:i A', 'g:i a', 'h:i a'];
            foreach ($formats as $fmt) {
                try {
                    $time = Carbon::createFromFormat($fmt, $timeInput);
                    break;
                } catch (\Exception $e) {
                    // try next
                }
            }

            // As a fallback try flexible parse
            if (!$time) {
                try {
                    $time = Carbon::parse($timeInput);
                } catch (\Exception $e) {
                    // invalid time
                }
            }

            if (!$time) {
                return back()->withErrors(['time' => 'The time field must be a valid time (e.g., 08:00 or 08:00 AM)'])->withInput();
            }

            // store as H:i:s
            $validated['time'] = $time->format('H:i:s');
        }

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
