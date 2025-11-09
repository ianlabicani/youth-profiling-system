<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangays = Barangay::orderBy('name')->paginate(15);
        return view('municipal.barangays.index', compact('barangays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('municipal.barangays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name',
        ]);

        Barangay::create($validated);

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barangay $barangay)
    {
        return view('municipal.barangays.show', compact('barangay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barangay $barangay)
    {
        return view('municipal.barangays.edit', compact('barangay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name,' . $barangay->id,
        ]);

        $barangay->update($validated);

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barangay $barangay)
    {
        $barangay->delete();

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay deleted successfully.');
    }
}
