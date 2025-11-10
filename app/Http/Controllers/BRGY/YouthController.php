<?php

namespace App\Http\Controllers\BRGY;

use App\Http\Controllers\Controller;
use App\Models\Youth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class YouthController extends Controller
{
    // 28 Barangays of Camalaniugan
    private $barangays = [
        'Abagao', 'Aglangan', 'Alilinu', 'Bagu', 'Baringasay',
        'Bitag Grande', 'Bitag Pequeño', 'Buyon', 'Casili Norte', 'Casili Sur',
        'Catotoran Norte', 'Catotoran Sur', 'Centro Norte (Pob.)', 'Centro Sur (Pob.)', 'Culao',
        'Dacal', 'Dammang', 'Jurisdiction', 'Lavilles', 'Magsaysay',
        'Mantang', 'Nami', 'Parabca', 'Sampa', 'Tabang',
        'Taggat Norte', 'Taggat Sur', 'Zingareng',
    ];

    // Map of puroks for each barangay
    private $puroksByBarangay = [
        'Abagao' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Aglangan' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Alilinu' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
        'Bagu' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Baringasay' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Bitag Grande' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Bitag Pequeño' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Buyon' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
        'Casili Norte' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Casili Sur' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Catotoran Norte' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Catotoran Sur' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Centro Norte (Pob.)' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 'Purok 6'],
        'Centro Sur (Pob.)' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
        'Culao' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dacal' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dammang' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Jurisdiction' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Lavilles' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Magsaysay' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Mantang' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Nami' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Parabca' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Sampa' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Tabang' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Taggat Norte' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Taggat Sur' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Zingareng' => ['Purok 1', 'Purok 2', 'Purok 3'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Youth::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('first_name', 'like', "%$search%")
                ->orWhere('middle_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('contact_number', 'like', "%$search%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sex filter
        if ($request->filled('sex')) {
            $query->where('sex', $request->input('sex'));
        }

        // Educational attainment filter
        if ($request->filled('educational_attainment')) {
            $query->where('educational_attainment', $request->input('educational_attainment'));
        }

        // Pagination
        $youths = $query->paginate(15);

        return view('brgy.youth.index', compact('youths'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return redirect()->route('brgy.youth.index')
                ->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        return view('brgy.youth.create', [
            'userBarangay' => $userBarangay,
            'puroksByBarangay' => $this->puroksByBarangay,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return back()->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'date_of_birth' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $birthDate = \Carbon\Carbon::parse($value);
                    $age = $birthDate->age;

                    if ($age < 15) {
                        $fail('The youth must be at least 15 years old to register.');
                    }
                    if ($age > 30) {
                        $fail('The youth must be 30 years old or younger to register.');
                    }
                },
            ],
            'sex' => 'required|in:Male,Female,Other',
            'purok' => 'required|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:youths,email',
            'educational_attainment' => 'required|string|max:255',
            'skills' => 'nullable|string',
            'status' => 'nullable|in:active,archived',
            'remarks' => 'nullable|string',
        ]);

        // Handle photo upload from file or camera capture (base64)
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('youth_photos', 'public');
            $validated['photo'] = $photoPath;
        } elseif ($request->filled('photo-capture')) {
            $dataUrl = $request->input('photo-capture');
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
                $extension = strtolower($matches[1]);
                if (! in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $extension = 'jpg';
                }
                $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $binary = base64_decode($data);
                $fileName = 'youth_photos/'.uniqid('cap_', true).'.'.$extension;
                Storage::disk('public')->put($fileName, $binary);
                $validated['photo'] = $fileName;
            }
        }

        // Automatically set the barangay_id from the logged-in user's barangay
        $validated['barangay_id'] = $userBarangay->id;

        try {
            Youth::create($validated);

            return redirect()->route('brgy.youth.index')->with('success', 'Youth record created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create youth record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);

            return back()->withInput()->withErrors(['general' => 'Failed to register youth. Please try again: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Youth $youth)
    {
        return view('brgy.youth.show', compact('youth'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Youth $youth)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return redirect()->route('brgy.youth.index')
                ->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        // Ensure the youth belongs to the user's barangay
        if ($youth->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.youth.index')
                ->withErrors(['error' => 'You can only edit youth from your barangay.']);
        }

        return view('brgy.youth.edit', [
            'youth' => $youth,
            'userBarangay' => $userBarangay,
            'puroksByBarangay' => $this->puroksByBarangay,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Youth $youth)
    {
        // Get the barangay of the logged-in user
        $userBarangay = auth()->user()->barangays()->first();

        if (! $userBarangay) {
            return back()->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        // Ensure the youth belongs to the user's barangay
        if ($youth->barangay_id !== $userBarangay->id) {
            return redirect()->route('brgy.youth.index')
                ->withErrors(['error' => 'You can only edit youth from your barangay.']);
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'date_of_birth' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $birthDate = \Carbon\Carbon::parse($value);
                    $age = $birthDate->age;

                    if ($age < 15) {
                        $fail('The youth must be at least 15 years old to register.');
                    }
                    if ($age > 30) {
                        $fail('The youth must be 30 years old or younger to register.');
                    }
                },
            ],
            'sex' => 'required|in:Male,Female,Other',
            'purok' => 'required|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:youths,email,'.$youth->id,
            'educational_attainment' => 'required|string|max:255',
            'skills' => 'nullable|string',
            'status' => 'nullable|in:active,archived',
            'remarks' => 'nullable|string',
        ]);

        // Handle photo upload (file or camera capture) on update
        if ($request->hasFile('photo')) {
            if ($youth->photo) {
                Storage::disk('public')->delete($youth->photo);
            }
            $photoPath = $request->file('photo')->store('youth_photos', 'public');
            $validated['photo'] = $photoPath;
        } elseif ($request->filled('photo-capture')) {
            if ($youth->photo) {
                Storage::disk('public')->delete($youth->photo);
            }
            $dataUrl = $request->input('photo-capture');
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
                $extension = strtolower($matches[1]);
                if (! in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $extension = 'jpg';
                }
                $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $binary = base64_decode($data);
                $fileName = 'youth_photos/'.uniqid('cap_', true).'.'.$extension;
                Storage::disk('public')->put($fileName, $binary);
                $validated['photo'] = $fileName;
            }
        }

        try {
            $youth->update($validated);

            return redirect()->route('brgy.youth.show', $youth)->with('success', 'Youth record updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update youth record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'youth_id' => $youth->id,
            ]);

            return back()->withInput()->withErrors(['general' => 'Failed to update youth record. Please try again: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Youth $youth)
    {
        // Delete photo if exists
        if ($youth->photo) {
            Storage::disk('public')->delete($youth->photo);
        }

        $youth->delete();

        return redirect()->route('brgy.youth.index')->with('success', 'Youth record deleted successfully.');
    }

    /**
     * Display heatmap of youth locations.
     */
    public function heatmap()
    {
        $youths = Youth::all();

        return view('brgy.youth.heatmap', ['youths' => $youths]);
    }
}
