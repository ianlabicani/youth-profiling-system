<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Youth;

class YouthController extends Controller
{
    /**
     * Display a listing of youths for a barangay.
     */
    public function index(Barangay $barangay)
    {
        $youths = $barangay->youths()->paginate(15);

        return view('municipal.barangays.youth.index', compact('barangay', 'youths'));
    }

    /**
     * Display the specified youth.
     */
    public function show(Youth $youth)
    {
        return view('municipal.youths.show', compact('youth'));
    }
}
