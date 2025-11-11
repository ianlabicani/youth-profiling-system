<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SKCouncil;
use Illuminate\Http\Request;

class CouncilController extends Controller
{
    /**
     * Display all SK Councils for public viewing
     */
    public function index(Request $request)
    {
        $skCouncils = SKCouncil::with('barangay')
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->whereHas('barangay', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('barangay_id'), function ($query) use ($request) {
                $query->where('barangay_id', $request->input('barangay_id'));
            })
            ->orderBy('id', 'asc')
            ->paginate(12);

        $barangays = \App\Models\Barangay::orderBy('name', 'asc')->get();

        return view('public.councils.index', compact('skCouncils', 'barangays'));
    }

    /**
     * Display a single SK Council with full details
     */
    public function show(SKCouncil $skCouncil)
    {
        $skCouncil->load([
            'barangay',
            'chairperson',
            'secretary',
            'treasurer',
        ]);

        // Fetch kagawads (council members) for display
        $kagawads = $skCouncil->kagawads();

        return view('public.councils.show', compact('skCouncil', 'kagawads'));
    }
}
