<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StoreStudentJob;
use Illuminate\Support\Facades\Mail;
use App\Mail\ImportCompletedEmail;

class StudentController extends Controller
{
    public function index()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv')->getRealPath(), 'r');
        $header = fgetcsv($file);

        $total = 0;
        $imported = 0;
        $failed = 0;
        $details = [];

        while (($row = fgetcsv($file)) !== false) {
            $total++;

            $name = isset($row[0]) ? trim($row[0]) : '';
            $class = isset($row[1]) ? trim($row[1]) : '';
            $phone = isset($row[2]) ? trim($row[2]) : '';

            $recordData = [
                'name' => $name ?: 'N/A',
                'class' => $class ?: 'N/A',
                'phone' => $phone ?: 'N/A',
            ];

            // Basic validation
            if (empty($name) || empty($class) || empty($phone)) {
                $failed++;
                $recordData['status'] = 'Failed';
                $recordData['reason'] = 'Missing fields';
                $details[] = $recordData;
                continue;
            }

            try {
                // Check for duplicate
                $exists = \App\Models\Student::where('name', $name)
                    ->where('class', $class)
                    ->where('phone', $phone)
                    ->exists();

                if ($exists) {
                    $failed++; // Count duplicates as failed/skipped
                    $recordData['status'] = 'Skipped';
                    $recordData['reason'] = 'Duplicate entry';
                    $details[] = $recordData;
                    continue;
                }

                \App\Models\Student::create([
                    'name' => $name,
                    'class' => $class,
                    'phone' => $phone,
                ]);
                $imported++;
                $recordData['status'] = 'Success';
                $recordData['reason'] = 'Imported';
                $details[] = $recordData;
            } catch (\Exception $e) {
                $failed++;
                $recordData['status'] = 'Failed';
                $recordData['reason'] = 'Database error';
                $details[] = $recordData;
            }
        }

        fclose($file);

        $importData = [
            'total' => $total,
            'imported' => $imported,
            'failed' => $failed,
            'details' => $details,
        ];




        try {
            Mail::to('admin@test.com')->queue(new ImportCompletedEmail($importData));
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total' => $total,
                'imported' => $imported,
                'failed' => $failed,
                'details' => $details,
            ]);
        }

        return back()->with('import_stats', [
            'total' => $total,
            'imported' => $imported,
            'failed' => $failed,
        ]);
    }
}