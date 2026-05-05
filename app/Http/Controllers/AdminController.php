<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());

            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $rows = $sheet->toArray();

                if (count($rows) < 4) continue;

                $headers = $rows[1];
                $subTypes = $rows[2];

                $subjects = [];
                for ($i = 2; $i < count($headers); $i++) {
                    $headerLower = strtolower(trim($headers[$i] ?? ''));
                    if (str_contains($headerLower, 'total mark') || str_contains($headerLower, 'total obt') || $headerLower === 'total hide') {
                        break;
                    }

                    if (!empty(trim($headers[$i] ?? ''))) {
                        $subjectName = trim($headers[$i]);
                        $subColumns = [];
                        $type = trim($subTypes[$i] ?? '') ?: 'TE';
                        $subColumns[$type] = $i;

                        if ($i + 1 < count($headers) && empty(trim($headers[$i+1] ?? '')) && !empty(trim($subTypes[$i+1] ?? ''))) {
                            $subColumns[trim($subTypes[$i+1])] = $i + 1;
                        }
                        $subjects[$subjectName] = $subColumns;
                    }
                }

                $totalMarksIdx = null;
                $totalObtMarksIdx = null;
                $daiyaRankIdx = null;
                $collegeRankIdx = null;
                $statusIdx = null;

                for ($i = 2; $i < count($headers); $i++) {
                    $header = strtolower(trim($headers[$i] ?? ''));
                    if (str_starts_with($header, 'total marks')) $totalMarksIdx = $i;
                    if (str_starts_with($header, 'total obt')) $totalObtMarksIdx = $i;
                    if (str_starts_with($header, 'daiya rank')) $daiyaRankIdx = $i;
                    if (str_starts_with($header, 'college rank')) $collegeRankIdx = $i;
                    if ($header === 'status') $statusIdx = $i;
                }

                for ($r = 3; $r < count($rows); $r++) {
                    $row = $rows[$r];
                    $regNo = trim($row[0] ?? '');
                    $name = trim($row[1] ?? '');

                    if (empty($regNo)) continue;

                    $marksData = [];
                    foreach ($subjects as $subjName => $subCols) {
                        $subjectData = [];
                        foreach ($subCols as $type => $colIdx) {
                            $val = trim($row[$colIdx] ?? '');
                            if ($val !== '') {
                                $subjectData[$type] = $val;
                            }
                        }
                        if (count($subjectData) > 0) {
                            $marksData[$subjName] = $subjectData;
                        }
                    }

                    ExamResult::updateOrCreate(
                        ['reg_no' => $regNo],
                        [
                            'batch' => $sheetName,
                            'name' => $name,
                            'marks_data' => $marksData,
                            'total_marks' => $totalMarksIdx !== null ? trim($row[$totalMarksIdx] ?? '') : null,
                            'total_obt_marks' => $totalObtMarksIdx !== null ? trim($row[$totalObtMarksIdx] ?? '') : null,
                            'daiya_rank' => $daiyaRankIdx !== null ? trim($row[$daiyaRankIdx] ?? '') : null,
                            'college_rank' => $collegeRankIdx !== null ? trim($row[$collegeRankIdx] ?? '') : null,
                            'status' => $statusIdx !== null ? trim($row[$statusIdx] ?? '') : null,
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', 'Excel file imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
