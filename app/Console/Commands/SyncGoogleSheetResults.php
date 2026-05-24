<?php

namespace App\Console\Commands;

use App\Services\ExamResultImportService;
use Illuminate\Console\Command;
use Throwable;

class SyncGoogleSheetResults extends Command
{
    protected $signature = 'results:sync-google-sheet
                            {url? : Google Sheet URL (optional when GOOGLE_SHEET_URL is configured)}
                            {--force : Run even when GOOGLE_SHEET_AUTO_SYNC_ENABLED is false}';

    protected $description = 'Sync exam results directly from a Google Sheet URL';

    public function handle(ExamResultImportService $importService): int
    {
        $inputUrl = trim((string) $this->argument('url'));
        $configUrl = trim((string) config('results.google_sheet_url'));

        $shouldUseAutoSyncFlag = $inputUrl === '';
        if (
            $shouldUseAutoSyncFlag &&
            ! $this->option('force') &&
            ! (bool) config('results.google_sheet_auto_sync_enabled')
        ) {
            $this->line('Auto sync is disabled. Skipping Google Sheet sync.');
            return self::SUCCESS;
        }

        $url = $inputUrl !== '' ? $inputUrl : $configUrl;
        if ($url === '') {
            $this->error('No Google Sheet URL provided. Pass a URL or set GOOGLE_SHEET_URL in .env.');
            return self::FAILURE;
        }

        try {
            $summary = $importService->importFromGoogleSheetUrl($url);

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

