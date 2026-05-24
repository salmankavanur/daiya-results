<?php

namespace App\Console\Commands;

use App\Models\GoogleSheetSyncSetting;
use App\Services\ExamResultImportService;
use Illuminate\Console\Command;
use Throwable;

class SyncGoogleSheetResults extends Command
{
    protected $signature = 'results:sync-google-sheet
                            {url? : Google Sheet URL (optional, overrides saved admin URL)}
                            {--force : Force sync even when auto sync is disabled or interval is not due}
                            {--scheduled : Internal flag for scheduler-triggered run}';

    protected $description = 'Sync exam results directly from a Google Sheet URL';

    public function handle(ExamResultImportService $importService): int
    {
        $syncSetting = GoogleSheetSyncSetting::current();

        $inputUrl = trim((string) $this->argument('url'));
        $url = $inputUrl !== '' ? $inputUrl : trim((string) $syncSetting->google_sheet_url);

        $isScheduledRun = (bool) $this->option('scheduled');
        $isForcedRun = (bool) $this->option('force');

        if ($isScheduledRun && ! $isForcedRun && ! $syncSetting->isDueForSync()) {
            $this->line('Google Sheet sync skipped (not due yet or auto-sync disabled).');
            return self::SUCCESS;
        }

        if ($url === '') {
            $this->error('No Google Sheet URL configured. Save it from admin panel or pass URL directly.');
            return self::FAILURE;
        }

        try {
            $summary = $importService->importFromGoogleSheetUrl($url);

            $syncSetting->update([
                'last_synced_at' => now(),
            ]);

            $this->info('Google Sheet sync completed successfully.');
            $this->line('Sheets processed: '.($summary['sheets_processed'] ?? 0));
            $this->line('Students synced: '.($summary['students_synced'] ?? 0));
            $this->line('Created: '.($summary['students_created'] ?? 0));
            $this->line('Updated: '.($summary['students_updated'] ?? 0));
            $this->line('Unchanged: '.($summary['students_unchanged'] ?? 0));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Google Sheet sync failed: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
