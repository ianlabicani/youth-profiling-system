<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

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
        })->paginate(15);

        return view('municipal.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('municipal.accounts.create');
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
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(),
        ]);

        $barangayRole = Role::firstOrCreate(['name' => 'barangay']);
        $user->roles()->attach($barangayRole);

        return redirect()->route('municipal.accounts.index')
            ->with('success', 'Barangay account created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $account)
    {
        return view('municipal.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $account->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $account->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $account->update(['password' => bcrypt($validated['password'])]);
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
}
