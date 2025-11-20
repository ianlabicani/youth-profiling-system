<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangays = Barangay::orderBy('name')->paginate(15);

        return view('municipal.barangays.index', compact('barangays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('municipal.barangays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name',
        ]);

        Barangay::create($validated);

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barangay $barangay)
    {
        return view('municipal.barangays.show', compact('barangay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barangay $barangay)
    {
        return view('municipal.barangays.edit', compact('barangay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:barangays,name,'.$barangay->id,
        ]);

        $barangay->update($validated);

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barangay $barangay)
    {
        $barangay->delete();

        return redirect()->route('municipal.barangays.index')
            ->with('success', 'Barangay deleted successfully.');
    }

    public function youths(Barangay $barangay)
    {
        $query = $barangay->youths();

        // Apply search filter
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"])
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Apply sex filter
        if ($sex = request('sex')) {
            $query->where('sex', $sex);
        }

        // Apply purok filter
        if ($purok = request('purok')) {
            $query->where('purok', $purok);
        }

        $youths = $query->paginate(15);

        return view('municipal.barangays.youths.index', compact('barangay', 'youths'));
    }

    public function youthShow(Barangay $barangay, $id)
    {
        $youth = $barangay->youths()->where('id', $id)->firstOrFail();

        return view('municipal.barangays.youths.show', compact('barangay', 'youth'));
    }

    public function skCouncils(Barangay $barangay)
    {
        $skCouncils = $barangay->skCouncils()->paginate(15);

        return view('municipal.barangays.sk-councils.index', compact('barangay', 'skCouncils'));
    }

    public function skCouncilShow(Barangay $barangay, $id)
    {
        $skCouncil = $barangay->skCouncils()->where('id', $id)->firstOrFail();

        return view('municipal.barangays.sk-councils.show', compact('barangay', 'skCouncil'));
    }

    /**
     * Export barangays to PDF or Excel
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $barangays = Barangay::orderBy('name')->get();

        if ($format === 'excel') {
            return $this->exportToExcel($barangays);
        } else {
            return $this->exportToPdf($barangays);
        }
    }

    /**
     * Export to Excel format
     */
    private function exportToExcel($barangays)
    {
        $filename = 'barangays_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $data = [];
        $data[] = ['ID', 'Name', 'Created At'];

        foreach ($barangays as $barangay) {
            $data[] = [
                $barangay->id,
                $barangay->name,
                $barangay->created_at->format('Y-m-d H:i:s'),
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
    private function exportToPdf($barangays)
    {
        $data = [
            'barangays' => $barangays,
            'title' => 'Barangays Report',
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.barangays-pdf', $data);
        return $pdf->download('barangays_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
