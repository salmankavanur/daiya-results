<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\GoogleSheetSyncSetting;
use App\Services\ExamResultImportService;
use Throwable;

class AdminController extends Controller
{
    public function index()
    {
        $resultsCount = ExamResult::count();
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');
        $syncSetting = GoogleSheetSyncSetting::current();
        return view('dashboard', compact('resultsCount', 'batches', 'syncSetting'));
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
            'google_sheet_url' => ['nullable', 'url', 'max:2048', 'regex:/docs\.google\.com\/spreadsheets/i'],
        ], [
            'google_sheet_url.regex' => 'Please enter a valid Google Sheets URL.',
        ]);

        try {
            $syncSetting = GoogleSheetSyncSetting::current();
            $url = trim((string) $request->input('google_sheet_url', ''));
            if ($url === '') {
                $url = trim((string) $syncSetting->google_sheet_url);
            }

            if ($url === '') {
                return redirect()->back()->with('error', 'Google Sheet URL is not configured. Please save it first from admin settings.');
            }

            $summary = $importService->importFromGoogleSheetUrl($url);

            $syncSetting->update([
                'last_synced_at' => now(),
            ]);

            return redirect()->back()->with('success', $this->formatImportSummaryMessage('Google Sheet synced successfully.', $summary));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Google Sheet sync failed: ' . $e->getMessage());
        }
    }

    public function updateGoogleSheetSettings(Request $request)
    {
        $validated = $request->validate([
            'google_sheet_url' => ['nullable', 'url', 'max:2048', 'regex:/docs\.google\.com\/spreadsheets/i'],
            'auto_sync_enabled' => ['nullable', 'boolean'],
            'sync_interval_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
        ], [
            'google_sheet_url.regex' => 'Please enter a valid Google Sheets URL.',
        ]);

        $autoSyncEnabled = (bool) ($validated['auto_sync_enabled'] ?? false);
        $googleSheetUrl = trim((string) ($validated['google_sheet_url'] ?? ''));
        if ($autoSyncEnabled && $googleSheetUrl === '') {
            return redirect()->back()->with('error', 'Please provide a Google Sheet URL before enabling auto-sync.');
        }

        $syncSetting = GoogleSheetSyncSetting::current();
        $syncSetting->update([
            'google_sheet_url' => $googleSheetUrl !== '' ? $googleSheetUrl : null,
            'auto_sync_enabled' => $autoSyncEnabled,
            'sync_interval_minutes' => (int) $validated['sync_interval_minutes'],
        ]);

        return redirect()->back()->with('success', 'Google Sheet sync settings saved successfully.');
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
