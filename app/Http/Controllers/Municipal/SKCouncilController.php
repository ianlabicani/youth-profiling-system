<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\SKCouncil;

class SKCouncilController extends Controller
{
    /**
     * Display a listing of SK councils for a barangay.
     */
    public function index(Barangay $barangay)
    {
        $skCouncils = $barangay->skCouncils()->paginate(10);

        return view('municipal.sk-councils.index', compact('barangay', 'skCouncils'));
    }

    /**
     * Display the specified SK council.
     */
    public function show(Barangay $barangay, SKCouncil $skCouncil)
    {
        return view('municipal.barangays.sk-councils.show', compact('barangay', 'skCouncil'));
    }
}
