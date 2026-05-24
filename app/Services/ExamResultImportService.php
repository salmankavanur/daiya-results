<?php

namespace App\Services;

use App\Models\BatchSubject;
use App\Models\ExamResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use RuntimeException;
use Throwable;

class ExamResultImportService
{
    public function importFromFilePath(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);

        return $this->importFromSpreadsheet($spreadsheet);
    }

    public function importFromGoogleSheetUrl(string $googleSheetUrl): array
    {
        $googleSheetUrl = trim($googleSheetUrl);

        if ($googleSheetUrl === '') {
            throw new RuntimeException('Google Sheet URL cannot be empty.');
        }

        $spreadsheetId = $this->extractSpreadsheetId($googleSheetUrl);

        if (! $spreadsheetId) {
            throw new RuntimeException('Invalid Google Sheet URL. Use a URL like https://docs.google.com/spreadsheets/d/<sheet-id>/edit');
        }

        $downloadUrl = sprintf(
            'https://docs.google.com/spreadsheets/d/%s/export?format=xlsx',
            $spreadsheetId
        );

        $response = Http::timeout(60)->retry(2, 1000)->get($downloadUrl);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Failed to download Google Sheet. Ensure the sheet is shared with "Anyone with the link can view".'
                .' (HTTP '.$response->status().')'
            );
        }

        $body = $response->body();
        if ($body === '' || str_contains(strtolower($body), '<html')) {
            throw new RuntimeException(
                'Google returned HTML instead of an Excel file. Make sure the sheet is public or shared for view access.'
            );
        }

        $tempDir = storage_path('app/tmp');
        if (! is_dir($tempDir) && ! mkdir($tempDir, 0755, true) && ! is_dir($tempDir)) {
            throw new RuntimeException('Could not create temporary directory for Google Sheet sync.');
        }

        $tempPath = $tempDir.'/google-sheet-'.now()->format('YmdHis').'-'.Str::random(6).'.xlsx';
        if (file_put_contents($tempPath, $body) === false) {
            throw new RuntimeException('Could not save downloaded Google Sheet for processing.');
        }

        try {
            return $this->importFromFilePath($tempPath);
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    public function importFromSpreadsheet(Spreadsheet $spreadsheet): array
    {
        $stats = [
            'sheets_processed' => 0,
            'sheets_skipped' => 0,
            'students_synced' => 0,
            'students_created' => 0,
            'students_updated' => 0,
            'students_unchanged' => 0,
            'batches' => [],
        ];

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if (! $sheet) {
                $stats['sheets_skipped']++;
                continue;
            }

            $rows = $sheet->toArray();
            if (count($rows) < 4) {
                $stats['sheets_skipped']++;
                continue;
            }

            $stats['sheets_processed']++;
            $stats['batches'][] = $sheetName;

            $this->importSheetRows($sheetName, $rows, $stats);
            $this->ensureBatchRanks($sheetName);
        }

        $stats['batches'] = array_values(array_unique($stats['batches']));

        return $stats;
    }

    public function extractSpreadsheetId(string $url): ?string
    {
        if (preg_match('#/spreadsheets/d/([a-zA-Z0-9-_]+)#', $url, $matches) === 1) {
            return $matches[1];
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if (! is_string($query)) {
            return null;
        }

        parse_str($query, $queryParams);

        return isset($queryParams['id']) && is_string($queryParams['id'])
            ? $queryParams['id']
            : null;
    }

    private function importSheetRows(string $sheetName, array $rows, array &$stats): void
    {
        $headers = $rows[1];
        $subTypes = $rows[2];
        $subjects = $this->extractSubjects($sheetName, $headers, $subTypes);
        $metaIndexes = $this->extractMetaIndexes($headers);

        for ($rowIndex = 3; $rowIndex < count($rows); $rowIndex++) {
            $row = $rows[$rowIndex];

            $regNo = trim((string) ($row[0] ?? ''));
            $name = trim((string) ($row[1] ?? ''));

            if ($regNo === '') {
                continue;
            }

            $marksData = [];
            $calculatedTotalObt = 0.0;
            $passedAll = true;

            foreach ($subjects as $subjectName => $subCols) {
                $subjectData = [];
                $subjectTotal = 0.0;

                foreach ($subCols as $type => $columnIndex) {
                    $value = trim((string) ($row[$columnIndex] ?? ''));
                    if ($value !== '') {
                        $subjectData[$type] = $value;
                        $subjectTotal += (float) $value;
                    }
                }

                if ($subjectData !== []) {
                    $marksData[$subjectName] = $subjectData;
                    $calculatedTotalObt += $subjectTotal;

                    if ($subjectTotal < 35) {
                        $passedAll = false;
                    }
                }
            }

            if ($marksData === []) {
                continue;
            }

            $totalPossibleMarks = count($marksData) * 100;

            $excelTotalMarks = $this->valueAt($row, $metaIndexes['total_marks']);
            $excelTotalObt = $this->valueAt($row, $metaIndexes['total_obt_marks']);
            $excelDaiyaRank = $this->valueAt($row, $metaIndexes['daiya_rank']);
            $excelCollegeRank = $this->valueAt($row, $metaIndexes['college_rank']);
            $excelStatus = $this->valueAt($row, $metaIndexes['status']);
            $excelDob = $this->valueAt($row, $metaIndexes['dob']);

            $parsedDob = $this->parseDob($excelDob);
            $mappedStatus = $this->normalizeStatus($excelStatus);

            $result = ExamResult::updateOrCreate(
                ['reg_no' => $regNo],
                [
                    'batch' => $sheetName,
                    'name' => $name,
                    'dob' => $parsedDob,
                    'marks_data' => $marksData,
                    'total_marks' => $excelTotalMarks !== '' ? $excelTotalMarks : (string) $totalPossibleMarks,
                    'total_obt_marks' => $excelTotalObt !== '' ? $excelTotalObt : (string) $calculatedTotalObt,
                    'daiya_rank' => $excelDaiyaRank !== '' ? $excelDaiyaRank : null,
                    'college_rank' => $excelCollegeRank !== '' ? $excelCollegeRank : null,
                    'status' => $mappedStatus !== '' ? $mappedStatus : ($passedAll ? 'Passed' : 'Failed'),
                ]
            );

            $stats['students_synced']++;
            if ($result->wasRecentlyCreated) {
                $stats['students_created']++;
            } elseif ($result->wasChanged()) {
                $stats['students_updated']++;
            } else {
                $stats['students_unchanged']++;
            }
        }
    }

    private function extractSubjects(string $sheetName, array $headers, array $subTypes): array
    {
        $subjects = [];

        for ($i = 2; $i < count($headers); $i++) {
            $headerLower = strtolower(trim((string) ($headers[$i] ?? '')));
            if (
                str_contains($headerLower, 'total mark') ||
                str_contains($headerLower, 'total obt') ||
                $headerLower === 'total hide'
            ) {
                break;
            }

            $subjectName = trim((string) ($headers[$i] ?? ''));
            if ($subjectName === '') {
                continue;
            }

            $subColumns = [];
            $type = trim((string) ($subTypes[$i] ?? '')) ?: 'TE';
            $subColumns[$type] = $i;

            $nextHeader = trim((string) ($headers[$i + 1] ?? ''));
            $nextType = trim((string) ($subTypes[$i + 1] ?? ''));

            if ($nextHeader === '' && $nextType !== '') {
                $subColumns[$nextType] = $i + 1;
            }

            $subjects[$subjectName] = $subColumns;

            BatchSubject::firstOrCreate(
                ['batch' => $sheetName, 'name' => $subjectName],
                ['max_te' => 100, 'max_ce' => 0, 'pass_mark' => 35]
            );
        }

        return $subjects;
    }

    private function extractMetaIndexes(array $headers): array
    {
        $indexes = [
            'total_marks' => null,
            'total_obt_marks' => null,
            'daiya_rank' => null,
            'college_rank' => null,
            'status' => null,
            'dob' => null,
        ];

        for ($i = 2; $i < count($headers); $i++) {
            $header = strtolower(trim((string) ($headers[$i] ?? '')));
            $normalized = preg_replace('/\s+/', ' ', $header) ?? $header;

            if (str_starts_with($normalized, 'total marks')) {
                $indexes['total_marks'] = $i;
            }
            if (str_starts_with($normalized, 'total obt')) {
                $indexes['total_obt_marks'] = $i;
            }
            if (str_starts_with($normalized, 'daiya rank')) {
                $indexes['daiya_rank'] = $i;
            }
            if (str_starts_with($normalized, 'college rank')) {
                $indexes['college_rank'] = $i;
            }
            if ($normalized === 'status') {
                $indexes['status'] = $i;
            }
            if (in_array($normalized, ['date of birth', 'd/b', 'dob'], true)) {
                $indexes['dob'] = $i;
            }
        }

        return $indexes;
    }

    private function parseDob(string $rawDob): ?string
    {
        if ($rawDob === '') {
            return null;
        }

        try {
            if (is_numeric($rawDob)) {
                return ExcelDate::excelToDateTimeObject((float) $rawDob)->format('Y-m-d');
            }

            return Carbon::parse($rawDob)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    private function normalizeStatus(string $status): string
    {
        return match (strtolower($status)) {
            'pass', 'passed' => 'Passed',
            'fail', 'failed' => 'Failed',
            'debar' => 'Debar',
            'withheld' => 'Withheld',
            default => '',
        };
    }

    private function valueAt(array $row, ?int $index): string
    {
        if ($index === null) {
            return '';
        }

        return trim((string) ($row[$index] ?? ''));
    }

    private function ensureBatchRanks(string $sheetName): void
    {
        $batchResults = ExamResult::where('batch', $sheetName)
            ->orderByRaw('CAST(total_obt_marks AS DECIMAL(10,2)) DESC')
            ->get();

        $daiyaRank = 1;
        foreach ($batchResults as $result) {
            $status = strtoupper((string) $result->status);
            if (is_null($result->daiya_rank)) {
                if ($status !== 'PASSED') {
                    $result->update(['daiya_rank' => 'Not Eligible']);
                } else {
                    $result->update(['daiya_rank' => (string) $daiyaRank]);
                }
            }

            if ($status === 'PASSED') {
                $daiyaRank++;
            }
        }

        $groupedByBranch = $batchResults->groupBy(function (ExamResult $result) {
            return substr((string) $result->reg_no, 0, 2);
        });

        foreach ($groupedByBranch as $students) {
            $collegeRank = 1;
            foreach ($students as $result) {
                $status = strtoupper((string) $result->status);
                if (is_null($result->college_rank)) {
                    if ($status !== 'PASSED') {
                        $result->update(['college_rank' => 'Not Eligible']);
                    } else {
                        $result->update(['college_rank' => (string) $collegeRank]);
                    }
                }

                if ($status === 'PASSED') {
                    $collegeRank++;
                }
            }
        }
    }
}

