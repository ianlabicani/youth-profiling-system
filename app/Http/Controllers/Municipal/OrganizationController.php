<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Organization;
use App\Models\Youth;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /** Display a listing of organizations. */
    public function index()
    {
        $organizations = Organization::with('barangay')->paginate(15);

        return view('municipal.organizations.index', compact('organizations'));
    }

    /** Show the form for creating a new organization. */
    public function create()
    {
        $youths = Youth::orderBy('last_name')->get();
        $barangays = Barangay::orderBy('name')->get();

        return view('municipal.organizations.create', compact('youths', 'barangays'));
    }

    /** Search for youth members (AJAX endpoint) */
    public function searchYouth(Request $request)
    {
        $search = $request->input('search', '');
        $excludeIds = $request->input('exclude', '');

        $query = Youth::where('status', 'active');

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
                    'barangay' => $youth->barangay?->name,
                ];
            });

        return response()->json($youths);
    }

    /** Get a single youth member (AJAX endpoint) */
    public function getYouth(Request $request)
    {
        $id = $request->input('id');
        $youth = Youth::find($id);

        if (! $youth) {
            return response()->json(null, 404);
        }

        return response()->json([
            'id' => $youth->id,
            'name' => $youth->first_name.
                     ($youth->middle_name ? ' '.substr($youth->middle_name, 0, 1).'.' : '').
                     ' '.$youth->last_name,
        ]);
    }

    /** Store a newly created organization in storage. */
    public function store(Request $request)
    {
        // validate the simple scalar fields first
        $data = $request->validate([
            'president_id' => 'nullable|exists:youths,id',
            'vice_president_id' => 'nullable|exists:youths,id',
            'secretary_id' => 'nullable|exists:youths,id',
            'treasurer_id' => 'nullable|exists:youths,id',
            // committee_heads and members will be parsed below
            'committee_heads' => 'nullable',
            'members' => 'nullable',
            'description' => 'nullable|string',
        ]);

        // Parse committee_heads: accept JSON string or array
        $committeeHeadsInput = $request->input('committee_heads');
        $committeeHeads = [];
        if ($committeeHeadsInput) {
            if (is_string($committeeHeadsInput)) {
                $decoded = json_decode($committeeHeadsInput, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $committeeHeads = $decoded;
                }
            } elseif (is_array($committeeHeadsInput)) {
                // Each element might be a JSON string, so we need to decode
                foreach ($committeeHeadsInput as $item) {
                    if (is_string($item)) {
                        $decoded = json_decode($item, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $committeeHeads[] = $decoded;
                        }
                    } elseif (is_array($item)) {
                        $committeeHeads[] = $item;
                    }
                }
            }
        }

        // Validate inner committee heads entries (basic)
        $committeeHeads = array_values(array_filter($committeeHeads, function ($ch) {
            return isset($ch['name']) && ($ch['name'] !== '');
        }));

        // Parse members: accept comma string or array
        $membersInput = $request->input('members');
        $members = [];
        if ($membersInput) {
            if (is_string($membersInput)) {
                $members = array_filter(array_map('trim', explode(',', $membersInput)));
            } elseif (is_array($membersInput)) {
                $members = $membersInput;
            }
        }

        // Attach parsed arrays to data
        $data['committee_heads'] = $committeeHeads ?: null;
        $data['members'] = $members ?: null;

        $org = Organization::create($data);

        return redirect()->route('municipal.organizations.show', $org)->with('success', 'Organization created');
    }

    /** Display the specified organization. */
    public function show(Organization $organization)
    {
        // eager load relations
        $organization->load(['president', 'vicePresident', 'secretary', 'treasurer', 'barangay']);
        // fetch members and committee heads youths for display
        $memberIds = $organization->members ?? [];
        $members = $memberIds ? Youth::whereIn('id', $memberIds)->get() : collect();

        $committeeHeads = collect($organization->committee_heads ?? [])->map(function ($item) {
            $item['head'] = isset($item['head_id']) ? Youth::find($item['head_id']) : null;

            return $item;
        });

        return view('municipal.organizations.show', compact('organization', 'members', 'committeeHeads'));
    }

    /** Show the form for editing the specified organization. */
    public function edit(Organization $organization)
    {
        $youths = Youth::orderBy('last_name')->get();
        $barangays = Barangay::orderBy('name')->get();

        return view('municipal.organizations.edit', compact('organization', 'youths', 'barangays'));
    }

    /** Update the specified organization in storage. */
    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'president_id' => 'nullable|exists:youths,id',
            'vice_president_id' => 'nullable|exists:youths,id',
            'secretary_id' => 'nullable|exists:youths,id',
            'treasurer_id' => 'nullable|exists:youths,id',
            'committee_heads' => 'nullable',
            'members' => 'nullable',
            'description' => 'nullable|string',
        ]);

        // Parse committee_heads
        $committeeHeadsInput = $request->input('committee_heads');
        $committeeHeads = [];
        if ($committeeHeadsInput) {
            if (is_string($committeeHeadsInput)) {
                $decoded = json_decode($committeeHeadsInput, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $committeeHeads = $decoded;
                }
            } elseif (is_array($committeeHeadsInput)) {
                // Each element might be a JSON string, so we need to decode
                foreach ($committeeHeadsInput as $item) {
                    if (is_string($item)) {
                        $decoded = json_decode($item, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $committeeHeads[] = $decoded;
                        }
                    } elseif (is_array($item)) {
                        $committeeHeads[] = $item;
                    }
                }
            }
        }

        $committeeHeads = array_values(array_filter($committeeHeads, function ($ch) {
            return isset($ch['name']) && ($ch['name'] !== '');
        }));

        // Parse members
        $membersInput = $request->input('members');
        $members = [];
        if ($membersInput) {
            if (is_string($membersInput)) {
                $members = array_filter(array_map('trim', explode(',', $membersInput)));
            } elseif (is_array($membersInput)) {
                $members = $membersInput;
            }
        }

        $data['committee_heads'] = $committeeHeads ?: null;
        $data['members'] = $members ?: null;

        $organization->update($data);

        return redirect()->route('municipal.organizations.show', $organization)->with('success', 'Organization updated');
    }

    /** Remove the specified organization from storage. */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('municipal.organizations.index')->with('success', 'Organization deleted');
    }
}
