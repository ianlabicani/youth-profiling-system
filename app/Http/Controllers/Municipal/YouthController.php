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
    public function index()
    {
        $youths = Youth::paginate(15);

        return view('municipal.youths.index', compact('youths'));
    }

    /**
     * Display the specified youth.
     */
    public function show(Youth $youth)
    {
        return view('municipal.youths.show', compact('youth'));
    }

    /**
     * Display listing of out of school youths.
     */
    public function outOfSchool()
    {
        $query = Youth::query();

        // Apply search filter
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Apply barangay filter
        if ($barangayId = request('barangay_id')) {
            $query->where('barangay_id', $barangayId);
        }

        // Apply status filter
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Apply sex filter
        if ($sex = request('sex')) {
            $query->where('sex', $sex);
        }

        // Filter: Out of school = No educational attainment or null
        $query->whereNull('educational_attainment')
            ->orWhere('educational_attainment', '');

        $youths = $query->paginate(15);

        return view('municipal.youths.out-of-school', compact('youths'));
    }
}
