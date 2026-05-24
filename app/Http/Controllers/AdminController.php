<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Services\ExamResultImportService;
use Throwable;

class AdminController extends Controller
{
    public function index()
    {
        $resultsCount = ExamResult::count();
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');
        return view('dashboard', compact('resultsCount', 'batches'));
    }

    public function clear()
    {
        ExamResult::truncate();
        return redirect()->back()->with('success', 'All results cleared successfully.');
    }

    public function import(Request $request, ExamResultImportService $importService)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $summary = $importService->importFromFilePath($request->file('excel_file')->getPathname());

            return redirect()->back()->with('success', $this->formatImportSummaryMessage('Excel file imported successfully.', $summary));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function syncGoogleSheet(Request $request, ExamResultImportService $importService)
    {
        $request->validate([
            'google_sheet_url' => ['required', 'url', 'max:2048', 'regex:/docs\.google\.com\/spreadsheets/i'],
        ], [
            'google_sheet_url.regex' => 'Please enter a valid Google Sheets URL.',
        ]);

        try {
            $summary = $importService->importFromGoogleSheetUrl((string) $request->input('google_sheet_url'));

            return redirect()->back()->with('success', $this->formatImportSummaryMessage('Google Sheet synced successfully.', $summary));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Google Sheet sync failed: ' . $e->getMessage());
        }
    }

    private function formatImportSummaryMessage(string $prefix, array $summary): string
    {
        return $prefix.' '
            .'Sheets: '.($summary['sheets_processed'] ?? 0)
            .', Students synced: '.($summary['students_synced'] ?? 0)
            .' (created '.($summary['students_created'] ?? 0)
            .', updated '.($summary['students_updated'] ?? 0)
            .', unchanged '.($summary['students_unchanged'] ?? 0).').';
    }
}
