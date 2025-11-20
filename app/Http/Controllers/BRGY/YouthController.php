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
        'Abagao',
        'Afunan-Cabayu',
        'Agusi',
        'Alilinu',
        'Baggao',
        'Bantay',
        'Bulala',
        'Casili Norte',
        'Casili Sur',
        'Catotoran Norte',
        'Catotoran Sur',
        'Centro Norte (Poblacion)',
        'Centro Sur (Poblacion)',
        'Cullit',
        'Dacal-la Fugu',
        'Dammang Norte / Joaquin dela Cruz',
        'Dammang Sur / Felipe Tuzon',
        'Dugo',
        'Fusina',
        'Gen. Eduardo Batalla',
        'Jurisdiccion',
        'Luec',
        'Minanga',
        'Paragat',
        'Sapping',
        'Tagum',
        'Tuluttuging',
        'Ziminila',
    ];

    // Map of puroks for each barangay
    private $puroksByBarangay = [
        'Abagao' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Afunan-Cabayu' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Agusi' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
        'Alilinu' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Baggao' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Bantay' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Bulala' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Casili Norte' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Casili Sur' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Catotoran Norte' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Catotoran Sur' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Centro Norte (Poblacion)' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 'Purok 6'],
        'Centro Sur (Poblacion)' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
        'Cullit' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dacal-la Fugu' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dammang Norte / Joaquin dela Cruz' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dammang Sur / Felipe Tuzon' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Dugo' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Fusina' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Gen. Eduardo Batalla' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Jurisdiccion' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Luec' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Minanga' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Paragat' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Sapping' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Tagum' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
        'Tuluttuging' => ['Purok 1', 'Purok 2', 'Purok 3'],
        'Ziminila' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userBarangay = auth()->user()->barangays()->first();

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

        // Barangay filter - restrict to user's barangay
        if ($userBarangay) {
            $query->where('barangay_id', $userBarangay->id);
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
        $incomeRange = [
            'No Income',
            'Below 10,000',
            '10,000 - 20,000',
            '20,001 - 30,000',
            '30,001 - 40,000',
            '40,001 - 50,000',
            'Above 50,000',
        ];

        if (! $userBarangay) {
            return redirect()->route('brgy.youth.index')
                ->withErrors(['error' => 'You are not assigned to any barangay.']);
        }

        return view('brgy.youth.create', [
            'userBarangay' => $userBarangay,
            'puroksByBarangay' => $this->puroksByBarangay,
            'incomeRange' => $incomeRange,
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

        // Build email validation rule dynamically
        $emailRule = 'nullable|email|max:255';
        if ($request->filled('email')) {
            $emailRule .= '|unique:youths,email';
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
            'email' => $emailRule,
            'guardians' => 'nullable|array|max:2',
            'guardians.*.first_name' => 'required|string|max:255',
            'guardians.*.middle_name' => 'nullable|string|max:255',
            'guardians.*.last_name' => 'required|string|max:255',
            'siblings' => 'nullable|array',
            'siblings.*.first_name' => 'required|string|max:255',
            'siblings.*.middle_name' => 'nullable|string|max:255',
            'siblings.*.last_name' => 'required|string|max:255',
            'household_income' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
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

        $incomeRange = [
            'No Income',
            'Below 10,000',
            '10,000 - 20,000',
            '20,001 - 30,000',
            '30,001 - 40,000',
            '40,001 - 50,000',
            'Above 50,000',
        ];

        return view('brgy.youth.edit', [
            'youth' => $youth,
            'userBarangay' => $userBarangay,
            'puroksByBarangay' => $this->puroksByBarangay,
            'incomeRange' => $incomeRange,
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

        // Build email validation rule dynamically
        $emailRule = 'nullable|email|max:255';
        if ($request->filled('email') && $request->input('email') !== $youth->email) {
            $emailRule .= '|unique:youths,email';
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
            'email' => $emailRule,
            'guardians' => 'nullable|array|max:2',
            'guardians.*.first_name' => 'required|string|max:255',
            'guardians.*.middle_name' => 'nullable|string|max:255',
            'guardians.*.last_name' => 'required|string|max:255',
            'siblings' => 'nullable|array',
            'siblings.*.first_name' => 'required|string|max:255',
            'siblings.*.middle_name' => 'nullable|string|max:255',
            'siblings.*.last_name' => 'required|string|max:255',
            'household_income' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
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

        $userBarangay = auth()->user()->barangays()->first();
        $youths = Youth::where('barangay_id', $userBarangay->id)->get();

        return view('brgy.youth.heatmap', ['youths' => $youths]);
    }
}
