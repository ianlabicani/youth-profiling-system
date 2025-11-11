<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\SKCouncil;
use App\Models\Youth;
use Illuminate\Http\Request;

class SKCouncilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return redirect()->route('brgy.dashboard')
                ->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        // Get SK councils for the user's barangay, ordered by active status first
        $skCouncils = SKCouncil::where('barangay_id', $userBarangay->id)
            ->with(['chairperson', 'secretary', 'treasurer'])
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->get();

        return view('brgy.sk-councils.index', [
            'skCouncils' => $skCouncils,
            'userBarangay' => $userBarangay,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        // Check if there are any SK members available
        $hasSkMembers = Youth::where('barangay_id', $userBarangay->id)
            ->where('status', 'active')
            ->exists();

        return view('brgy.sk-councils.create', [
            'userBarangay' => $userBarangay,
            'hasSkMembers' => $hasSkMembers,
        ]);
    }

    /**
     * Search for youth members (AJAX endpoint)
     */
    public function searchYouth(Request $request)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return response()->json(['error' => 'You are not assigned to any barangay.'], 403);
        }

        $search = $request->input('search', '');
        $excludeIds = $request->input('exclude', '');

        $query = Youth::where('barangay_id', $userBarangay->id)
            ->where('status', 'active');

        // Parse exclude IDs from comma-separated string
        if (! empty($excludeIds)) {
            $excludeIdsArray = array_filter(array_map('trim', explode(',', $excludeIds)));
            if (! empty($excludeIdsArray)) {
                $query->whereNotIn('id', $excludeIdsArray);
            }
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('middle_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
        }

        $youths = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(20)
            ->get()
            ->map(function ($youth) {
                return [
                    'id' => $youth->id,
                    'name' => $youth->first_name.
                             ($youth->middle_name ? ' '.substr($youth->middle_name, 0, 1).'.' : '').
                             ' '.$youth->last_name,
                    'full_name' => $youth->first_name.' '.
                                  ($youth->middle_name ? $youth->middle_name.' ' : '').
                                  $youth->last_name,
                    'purok' => $youth->purok,
                ];
            });

        return response()->json($youths);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return back()->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        // Check if an active council already exists
        $existingActiveCouncil = SKCouncil::where('barangay_id', $userBarangay->id)
            ->where('is_active', true)
            ->first();
        if ($existingActiveCouncil) {
            return back()->withErrors(['error' => 'An active SK Council already exists for your barangay. Please deactivate it first.']);
        }

        $validated = $request->validate([
            'term' => 'required|string|max:255',
            'chairperson_id' => 'required|exists:youths,id',
            'secretary_id' => 'nullable|exists:youths,id',
            'treasurer_id' => 'nullable|exists:youths,id',
            'kagawad_ids' => 'nullable|array|max:7',
            'kagawad_ids.*' => 'exists:youths,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Default to active if not specified
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Ensure all selected youths belong to the user's barangay
        $youthIds = array_filter([
            $validated['chairperson_id'],
            $validated['secretary_id'] ?? null,
            $validated['treasurer_id'] ?? null,
        ]);

        if (! empty($validated['kagawad_ids'])) {
            $youthIds = array_merge($youthIds, $validated['kagawad_ids']);
        }

        $invalidYouths = Youth::whereIn('id', $youthIds)
            ->where(function ($query) use ($userBarangay) {
                $query->where('barangay_id', '!=', $userBarangay->id)
                    ->orWhere('status', '!=', 'active');
            })
            ->count();

        if ($invalidYouths > 0) {
            return back()->withInput()->withErrors([
                'error' => 'All selected members must be active from your barangay.',
            ]);
        }

        // Set the barangay_id
        $validated['barangay_id'] = $userBarangay->id;

        // If this council is being set as active, deactivate all other councils
        if (isset($validated['is_active']) && $validated['is_active']) {
            SKCouncil::where('barangay_id', $userBarangay->id)
                ->update(['is_active' => false]);
        }

        SKCouncil::create($validated);

        return redirect()->route('brgy.sk-councils.index')
            ->with('success', 'SK Council created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SKCouncil $skCouncil)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay || $skCouncil->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You can only view SK Councils from your barangay.']);
        }

        $skCouncil->load(['barangay', 'chairperson', 'secretary', 'treasurer']);

        return view('brgy.sk-councils.show', [
            'skCouncil' => $skCouncil,
            'userBarangay' => $userBarangay,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SKCouncil $skCouncil)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay || $skCouncil->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You can only edit SK Councils from your barangay.']);
        }

        // Check if there are any SK members available
        $hasSkMembers = Youth::where('barangay_id', $userBarangay->id)
            ->where('status', 'active')
            ->exists();

        return view('brgy.sk-councils.edit', [
            'skCouncil' => $skCouncil,
            'userBarangay' => $userBarangay,
            'hasSkMembers' => $hasSkMembers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SKCouncil $skCouncil)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay || $skCouncil->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You can only edit SK Councils from your barangay.']);
        }

        $validated = $request->validate([
            'term' => 'required|string|max:255',
            'chairperson_id' => 'required|exists:youths,id',
            'secretary_id' => 'nullable|exists:youths,id',
            'treasurer_id' => 'nullable|exists:youths,id',
            'kagawad_ids' => 'nullable|array|max:7',
            'kagawad_ids.*' => 'exists:youths,id',
            'is_active' => 'nullable|boolean',
        ]);

        // If activating this council, deactivate all other councils
        if (isset($validated['is_active']) && $validated['is_active']) {
            SKCouncil::where('barangay_id', $userBarangay->id)
                ->where('id', '!=', $skCouncil->id)
                ->update(['is_active' => false]);
        }

        // Ensure all selected youths belong to the user's barangay and are active
        $youthIds = array_filter([
            $validated['chairperson_id'],
            $validated['secretary_id'] ?? null,
            $validated['treasurer_id'] ?? null,
        ]);

        if (! empty($validated['kagawad_ids'])) {
            $youthIds = array_merge($youthIds, $validated['kagawad_ids']);
        }

        $invalidYouths = Youth::whereIn('id', $youthIds)
            ->where(function ($query) use ($userBarangay) {
                $query->where('barangay_id', '!=', $userBarangay->id)
                    ->orWhere('status', '!=', 'active');
            })
            ->count();

        if ($invalidYouths > 0) {
            return back()->withInput()->withErrors([
                'error' => 'All selected members must be active from your barangay.',
            ]);
        }

        $skCouncil->update($validated);

        return redirect()->route('brgy.sk-councils.show', $skCouncil)
            ->with('success', 'SK Council updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SKCouncil $skCouncil)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay || $skCouncil->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You can only delete SK Councils from your barangay.']);
        }

        $skCouncil->delete();

        return redirect()->route('brgy.sk-councils.index')
            ->with('success', 'SK Council deleted successfully.');
    }

    /**
     * Activate the specified SK Council and deactivate others.
     */
    public function activate(SKCouncil $skCouncil)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay || $skCouncil->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.sk-councils.index')
                ->withErrors(['error' => 'You can only activate SK Councils from your barangay.']);
        }

        // Deactivate all other councils in this barangay
        SKCouncil::where('barangay_id', $userBarangay->id)
            ->update(['is_active' => false]);

        // Activate this council
        $skCouncil->update(['is_active' => true]);

        return redirect()->route('brgy.sk-councils.index')
            ->with('success', 'SK Council activated successfully.');
    }
}
