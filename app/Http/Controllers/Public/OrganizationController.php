<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Youth;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display all organizations for public viewing
     */
    public function index(Request $request)
    {
        // Get organizations with their relationships
        $organizations = Organization::with([
            'president',
            'vicePresident',
            'secretary',
            'treasurer',
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('president', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('public.organizations.index', compact('organizations'));
    }

    /**
     * Display a single organization with full details
     */
    public function show(Organization $organization)
    {
        $organization->load([
            'president',
            'vicePresident',
            'secretary',
            'treasurer',
        ]);

        // Fetch committee heads youths for display
        $committeeHeads = collect($organization->committee_heads ?? [])->map(function ($item) {
            $item['head'] = isset($item['head_id']) ? Youth::find($item['head_id']) : null;

            return $item;
        });

        return view('public.organizations.show', compact('organization', 'committeeHeads'));
    }
}
