<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangayRole = Role::where('name', 'barangay')->first();
        $accounts = User::whereHas('roles', function ($query) use ($barangayRole) {
            $query->where('role_id', $barangayRole?->id);
        })->with('barangays')->paginate(15);

        return view('municipal.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangays = Barangay::orderBy('name')->get();

        return view('municipal.accounts.create', compact('barangays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'barangay_id' => 'nullable|exists:barangays,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(),
        ]);

        $barangayRole = Role::firstOrCreate(['name' => 'barangay']);
        $user->roles()->attach($barangayRole);

        // Assign to barangay if provided
        if ($validated['barangay_id']) {
            $user->barangays()->attach($validated['barangay_id']);
        }

        return redirect()->route('municipal.accounts.index')
            ->with('success', 'Barangay account created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(User $account)
    {
        return view('municipal.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $account)
    {
        $barangays = Barangay::orderBy('name')->get();
        $assignedBarangay = $account->barangays()->first();

        return view('municipal.accounts.edit', compact('account', 'barangays', 'assignedBarangay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$account->id,
            'password' => 'nullable|string|min:8|confirmed',
            'barangay_id' => 'nullable|exists:barangays,id',
        ]);

        $account->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $account->update(['password' => bcrypt($validated['password'])]);
        }

        // Update barangay assignment
        if ($validated['barangay_id']) {
            // Reassign user to the selected barangay
            $account->barangays()->sync([$validated['barangay_id']]);
        } else {
            // Remove all barangay assignments if none selected
            $account->barangays()->detach();
        }

        return redirect()->route('municipal.accounts.index')
            ->with('success', 'Barangay account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $account)
    {
        $account->delete();

        return redirect()->route('municipal.accounts.index')
            ->with('success', 'Barangay account deleted successfully.');
    }

    /**
     * Export accounts to PDF or Excel
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');

        $barangayRole = Role::where('name', 'barangay')->first();
        $accounts = User::whereHas('roles', function ($query) use ($barangayRole) {
            $query->where('role_id', $barangayRole?->id);
        })->with('barangays')->get();

        if ($format === 'excel') {
            return $this->exportToExcel($accounts);
        } else {
            return $this->exportToPdf($accounts);
        }
    }

    /**
     * Export to Excel format
     */
    private function exportToExcel($accounts)
    {
        $filename = 'barangay_accounts_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $data = [];
        $data[] = ['Name', 'Email', 'Barangay', 'Created At'];

        foreach ($accounts as $account) {
            $barangay = $account->barangays()->first()?->name ?? 'Not Assigned';
            $data[] = [
                $account->name,
                $account->email,
                $barangay,
                $account->created_at->format('Y-m-d H:i:s'),
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
    private function exportToPdf($accounts)
    {
        $data = [
            'accounts' => $accounts,
            'title' => 'Barangay Accounts Report',
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.accounts-pdf', $data);
        return $pdf->download('barangay_accounts_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
