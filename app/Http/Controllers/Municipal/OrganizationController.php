<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Youth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /** Display a listing of organizations. */
    public function index()
    {
        $organizations = Organization::paginate(15);

        return view('municipal.organizations.index', compact('organizations'));
    }

    /** Show the form for creating a new organization. */
    public function create()
    {
        return view('municipal.organizations.create');
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
            'name' => 'nullable|string|max:255',
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
        $organization->load(['president', 'vicePresident', 'secretary', 'treasurer']);
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

        return view('municipal.organizations.edit', compact('organization', 'youths'));
    }

    /** Update the specified organization in storage. */
    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
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

    /**
     * Export organizations to PDF or Excel
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $organizations = Organization::all();

        if ($format === 'excel') {
            return $this->exportToExcel($organizations);
        } else {
            return $this->exportToPdf($organizations);
        }
    }

    /**
     * Export to Excel format
     */
    private function exportToExcel($organizations)
    {
        $filename = 'organizations_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $data = [];
        $data[] = ['ID', 'Name', 'Barangay', 'President', 'Vice President', 'Secretary', 'Treasurer', 'Members', 'Created At'];

        foreach ($organizations as $org) {
            $barangay = $org->barangay?->name ?? 'N/A';
            $president = $org->president ? $org->president->first_name . ' ' . $org->president->last_name : 'N/A';
            $vicePresident = $org->vicePresident ? $org->vicePresident->first_name . ' ' . $org->vicePresident->last_name : 'N/A';
            $secretary = $org->secretary ? $org->secretary->first_name . ' ' . $org->secretary->last_name : 'N/A';
            $treasurer = $org->treasurer ? $org->treasurer->first_name . ' ' . $org->treasurer->last_name : 'N/A';
            $memberCount = count($org->members ?? []);

            $data[] = [
                $org->id,
                $org->name ?? '',
                $barangay,
                $president,
                $vicePresident,
                $secretary,
                $treasurer,
                $memberCount,
                $org->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF format
     */
    private function exportToPdf($organizations)
    {
        $data = [
            'organizations' => $organizations,
            'title' => 'Organizations Report',
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.organizations-pdf', $data);
        return $pdf->download('organizations_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export all organizations grouped by organization with full details
     */
    public function exportGrouped(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $organizations = Organization::with('barangay', 'president', 'vicePresident', 'secretary', 'treasurer')->get();

        if ($format === 'excel') {
            return $this->exportGroupedToExcel($organizations);
        } else {
            return $this->exportGroupedToPdf($organizations);
        }
    }

    /**
     * Export grouped organizations to Excel
     */
    private function exportGroupedToExcel($organizations)
    {
        $filename = 'organizations_grouped_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($organizations) {
            $file = fopen('php://output', 'w');

            foreach ($organizations as $organization) {
                // Organization header
                fputcsv($file, ['ORGANIZATION: ' . ($organization->name ?? 'Organization #' . $organization->id)]);
                fputcsv($file, ['Barangay', $organization->barangay?->name ?? 'N/A']);
                fputcsv($file, ['Description', $organization->description ?? '']);
                fputcsv($file, ['Total Members', count($organization->members ?? [])]);
                fputcsv($file, []);

                // Officers
                fputcsv($file, ['Position', 'Name', 'Contact']);

                $president = $organization->president ?
                    $organization->president->first_name . ' ' . ($organization->president->middle_name ? substr($organization->president->middle_name, 0, 1) . '. ' : '') . $organization->president->last_name
                    : 'Not Assigned';
                fputcsv($file, ['President', $president, $organization->president?->contact_number ?? '']);

                $vicePresident = $organization->vicePresident ?
                    $organization->vicePresident->first_name . ' ' . ($organization->vicePresident->middle_name ? substr($organization->vicePresident->middle_name, 0, 1) . '. ' : '') . $organization->vicePresident->last_name
                    : 'Not Assigned';
                fputcsv($file, ['Vice President', $vicePresident, $organization->vicePresident?->contact_number ?? '']);

                $secretary = $organization->secretary ?
                    $organization->secretary->first_name . ' ' . ($organization->secretary->middle_name ? substr($organization->secretary->middle_name, 0, 1) . '. ' : '') . $organization->secretary->last_name
                    : 'Not Assigned';
                fputcsv($file, ['Secretary', $secretary, $organization->secretary?->contact_number ?? '']);

                $treasurer = $organization->treasurer ?
                    $organization->treasurer->first_name . ' ' . ($organization->treasurer->middle_name ? substr($organization->treasurer->middle_name, 0, 1) . '. ' : '') . $organization->treasurer->last_name
                    : 'Not Assigned';
                fputcsv($file, ['Treasurer', $treasurer, $organization->treasurer?->contact_number ?? '']);

                fputcsv($file, []);

                // Members
                if ($organization->members && count($organization->members) > 0) {
                    fputcsv($file, ['Members']);
                    fputcsv($file, ['Name', 'Age', 'Sex', 'Contact', 'Email', 'Status', 'Barangay']);

                    foreach ($organization->members as $member) {
                        fputcsv($file, [
                            ($member['first_name'] ?? '') . ' ' . ($member['middle_name'] ? substr($member['middle_name'], 0, 1) . '. ' : '') . ($member['last_name'] ?? ''),
                            $member['age'] ?? '',
                            $member['sex'] ?? '',
                            $member['contact_number'] ?? '',
                            $member['email'] ?? '',
                            $member['status'] ?? '',
                            $member['barangay_name'] ?? '',
                        ]);
                    }
                }

                fputcsv($file, []);
                fputcsv($file, ['---']);
                fputcsv($file, []);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export grouped organizations to PDF
     */
    private function exportGroupedToPdf($organizations)
    {
        $data = [
            'organizations' => $organizations,
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.organizations-grouped-pdf', $data);
        return $pdf->download('organizations_grouped_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export single organization
     */
    public function exportSingle(Organization $organization, string $format = 'pdf')
    {
        if ($format === 'excel') {
            return $this->exportSingleToExcel($organization);
        } else {
            return $this->exportSingleToPdf($organization);
        }
    }

    /**
     * Export single organization to Excel format with members
     */
    private function exportSingleToExcel(Organization $organization)
    {
        $filename = 'organization_' . Str::slug($organization->name) . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($organization) {
            $file = fopen('php://output', 'w');

            // Organization Header
            fputcsv($file, ['ORGANIZATION REPORT']);
            fputcsv($file, []);
            fputcsv($file, ['Organization Name', $organization->name ?? '']);
            fputcsv($file, ['Barangay', $organization->barangay?->name ?? 'N/A']);
            fputcsv($file, ['Description', $organization->description ?? '']);
            fputcsv($file, ['Created', $organization->created_at->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Officers Section
            fputcsv($file, ['OFFICERS']);
            fputcsv($file, ['Position', 'Name', 'Contact Number']);

            $president = $organization->president ?
                $organization->president->first_name . ' ' . ($organization->president->middle_name ? substr($organization->president->middle_name, 0, 1) . '. ' : '') . $organization->president->last_name
                : 'Not Assigned';
            fputcsv($file, ['President', $president, $organization->president?->contact_number ?? '']);

            $vicePresident = $organization->vicePresident ?
                $organization->vicePresident->first_name . ' ' . ($organization->vicePresident->middle_name ? substr($organization->vicePresident->middle_name, 0, 1) . '. ' : '') . $organization->vicePresident->last_name
                : 'Not Assigned';
            fputcsv($file, ['Vice President', $vicePresident, $organization->vicePresident?->contact_number ?? '']);

            $secretary = $organization->secretary ?
                $organization->secretary->first_name . ' ' . ($organization->secretary->middle_name ? substr($organization->secretary->middle_name, 0, 1) . '. ' : '') . $organization->secretary->last_name
                : 'Not Assigned';
            fputcsv($file, ['Secretary', $secretary, $organization->secretary?->contact_number ?? '']);

            $treasurer = $organization->treasurer ?
                $organization->treasurer->first_name . ' ' . ($organization->treasurer->middle_name ? substr($organization->treasurer->middle_name, 0, 1) . '. ' : '') . $organization->treasurer->last_name
                : 'Not Assigned';
            fputcsv($file, ['Treasurer', $treasurer, $organization->treasurer?->contact_number ?? '']);

            fputcsv($file, []);

            // Members Section
            fputcsv($file, ['MEMBERS (' . count($organization->members ?? []) . ')']);
            fputcsv($file, ['Name', 'Age', 'Sex', 'Contact Number', 'Email', 'Status', 'Barangay']);

            if ($organization->members && count($organization->members) > 0) {
                foreach ($organization->members as $member) {
                    fputcsv($file, [
                        ($member['first_name'] ?? '') . ' ' . ($member['middle_name'] ? substr($member['middle_name'], 0, 1) . '. ' : '') . ($member['last_name'] ?? ''),
                        $member['age'] ?? '',
                        $member['sex'] ?? '',
                        $member['contact_number'] ?? '',
                        $member['email'] ?? '',
                        $member['status'] ?? '',
                        $member['barangay_name'] ?? '',
                    ]);
                }
            }

            fputcsv($file, []);

            // Committee Heads Section
            if ($organization->committee_heads && count($organization->committee_heads) > 0) {
                fputcsv($file, ['COMMITTEE HEADS']);
                fputcsv($file, ['Name', 'Committee', 'Contact']);

                foreach ($organization->committee_heads as $head) {
                    fputcsv($file, [
                        $head['name'] ?? '',
                        $head['committee'] ?? '',
                        $head['contact'] ?? '',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export single organization to PDF format with members
     */
    private function exportSingleToPdf(Organization $organization)
    {
        // Fetch members
        $memberIds = $organization->members ?? [];
        $members = $memberIds ? Youth::whereIn('id', $memberIds)->get() : collect();

        // Enrich committee heads with Youth models
        $committeeHeads = collect($organization->committee_heads ?? [])->map(function ($item) {
            $item['head'] = isset($item['head_id']) ? Youth::find($item['head_id']) : null;
            return $item;
        })->toArray();

        // Format members data for PDF
        $membersData = $members->map(function ($member) use ($organization) {
            $age = $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->age : null;
            return [
                'first_name' => $member->first_name,
                'middle_name' => $member->middle_name,
                'last_name' => $member->last_name,
                'age' => $age,
                'sex' => $member->sex,
                'contact_number' => $member->contact_number,
                'email' => $member->email,
                'status' => $member->status,
                'barangay_name' => $member->barangay?->name ?? 'N/A',
            ];
        })->toArray();

        $data = [
            'organization' => $organization,
            'committeeHeads' => $committeeHeads,
            'members' => $membersData,
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.organization-single-pdf', $data);
        return $pdf->download('organization_' . Str::slug($organization->name) . '_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
