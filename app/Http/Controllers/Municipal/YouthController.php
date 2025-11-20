<?php

namespace App\Http\Controllers\Municipal;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Youth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Export youths to PDF or Excel
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');

        $query = Youth::query();

        // Apply search filter
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Apply barangay filter
        if ($barangayId = $request->query('barangay_id')) {
            $query->where('barangay_id', $barangayId);
        }

        // Apply status filter
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Apply sex filter
        if ($sex = $request->query('sex')) {
            $query->where('sex', $sex);
        }

        $youths = $query->get();

        if ($format === 'excel') {
            return $this->exportToExcel($youths);
        } else {
            return $this->exportToPdf($youths);
        }
    }

    /**
     * Export to Excel format
     */
    private function exportToExcel($youths)
    {
        $filename = 'youths_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $data = [];
        $data[] = ['ID', 'Name', 'Email', 'Contact', 'Barangay', 'Sex', 'Status', 'Created At'];

        foreach ($youths as $youth) {
            $barangay = $youth->barangay?->name ?? 'N/A';
            $data[] = [
                $youth->id,
                $youth->first_name . ' ' . ($youth->middle_name ? substr($youth->middle_name, 0, 1) . '. ' : '') . $youth->last_name,
                $youth->email ?? '',
                $youth->contact_number ?? '',
                $barangay,
                $youth->sex ?? '',
                $youth->status ?? '',
                $youth->created_at->format('Y-m-d H:i:s'),
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
    private function exportToPdf($youths)
    {
        $data = [
            'youths' => $youths,
            'title' => 'Youth Records Report',
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.youths-pdf', $data);
        return $pdf->download('youths_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export out-of-school youths to PDF or Excel
     */
    public function exportOutOfSchool(Request $request)
    {
        $format = $request->query('format', 'pdf');

        $query = Youth::query();

        // Apply search filter
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Apply barangay filter
        if ($barangayId = $request->query('barangay_id')) {
            $query->where('barangay_id', $barangayId);
        }

        // Apply status filter
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Apply sex filter
        if ($sex = $request->query('sex')) {
            $query->where('sex', $sex);
        }

        // Filter: Out of school = No educational attainment or null
        $query->where(function ($q) {
            $q->whereNull('educational_attainment')
                ->orWhere('educational_attainment', '');
        });

        $youths = $query->get();

        if ($format === 'excel') {
            return $this->exportOutOfSchoolToExcel($youths);
        } else {
            return $this->exportOutOfSchoolToPdf($youths);
        }
    }

    /**
     * Export out-of-school youths to Excel format
     */
    private function exportOutOfSchoolToExcel($youths)
    {
        $filename = 'out_of_school_youths_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $data = [];
        $data[] = ['ID', 'Name', 'Email', 'Contact', 'Barangay', 'Sex', 'Status', 'Created At'];

        foreach ($youths as $youth) {
            $barangay = $youth->barangay?->name ?? 'N/A';
            $data[] = [
                $youth->id,
                $youth->first_name . ' ' . ($youth->middle_name ? substr($youth->middle_name, 0, 1) . '. ' : '') . $youth->last_name,
                $youth->email ?? '',
                $youth->contact_number ?? '',
                $barangay,
                $youth->sex ?? '',
                $youth->status ?? '',
                $youth->created_at->format('Y-m-d H:i:s'),
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
     * Export out-of-school youths to PDF format
     */
    private function exportOutOfSchoolToPdf($youths)
    {
        $data = [
            'youths' => $youths,
            'title' => 'Out of School Youths Report',
            'date' => now()->format('Y-m-d H:i:s'),
        ];

        $pdf = Pdf::loadView('exports.out-of-school-youths-pdf', $data);
        return $pdf->download('out_of_school_youths_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
