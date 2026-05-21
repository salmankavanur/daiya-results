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
                        
                        // Auto-register subject to management module
                        \App\Models\BatchSubject::firstOrCreate(
                            ['batch' => $sheetName, 'name' => $subjectName],
                            ['max_te' => 100, 'max_ce' => 0, 'pass_mark' => 35]
                        );
                    }
                }

                $totalMarksIdx = null;
                $totalObtMarksIdx = null;
                $daiyaRankIdx = null;
                $collegeRankIdx = null;
                $statusIdx = null;
                $dobIdx = null;

                for ($i = 2; $i < count($headers); $i++) {
                    $header = strtolower(trim($headers[$i] ?? ''));
                    if (str_starts_with($header, 'total marks')) $totalMarksIdx = $i;
                    if (str_starts_with($header, 'total obt')) $totalObtMarksIdx = $i;
                    if (str_starts_with($header, 'daiya rank')) $daiyaRankIdx = $i;
                    if (str_starts_with($header, 'college rank')) $collegeRankIdx = $i;
                    if ($header === 'status') $statusIdx = $i;
                    if ($header === 'date of birth' || $header === 'd/b' || $header === 'dob') $dobIdx = $i;
                }

                for ($r = 3; $r < count($rows); $r++) {
                    $row = $rows[$r];
                    $regNo = trim($row[0] ?? '');
                    $name = trim($row[1] ?? '');

                    if (empty($regNo)) continue;

                    $marksData = [];
                    $calculatedTotalObt = 0;
                    $passedAll = true;

                    foreach ($subjects as $subjName => $subCols) {
                        $subjectData = [];
                        $subjectTotal = 0;
                        foreach ($subCols as $type => $colIdx) {
                            $val = trim($row[$colIdx] ?? '');
                            if ($val !== '') {
                                $subjectData[$type] = $val;
                                $subjectTotal += (float) $val;
                            }
                        }
                        if (count($subjectData) > 0) {
                            $marksData[$subjName] = $subjectData;
                            $calculatedTotalObt += $subjectTotal;
                            
                            // Basic pass criteria assumption (>= 35)
                            if ($subjectTotal < 35) {
                                $passedAll = false;
                            }
                        }
                    }

                    // Only process students who actually have marks
                    if (count($marksData) === 0) continue;

                    $totalPossibleMarks = count($marksData) * 100;

                    $excelTotalMarks = $totalMarksIdx !== null ? trim($row[$totalMarksIdx] ?? '') : '';
                    $excelTotalObt = $totalObtMarksIdx !== null ? trim($row[$totalObtMarksIdx] ?? '') : '';
                    $excelDaiyaRank = $daiyaRankIdx !== null ? trim($row[$daiyaRankIdx] ?? '') : '';
                    $excelCollegeRank = $collegeRankIdx !== null ? trim($row[$collegeRankIdx] ?? '') : '';
                    $excelStatus = $statusIdx !== null ? trim($row[$statusIdx] ?? '') : '';
                    $excelDob = $dobIdx !== null ? trim($row[$dobIdx] ?? '') : '';

                    $parsedDob = null;
                    if (!empty($excelDob)) {
                        try {
                            if (is_numeric($excelDob)) {
                                $parsedDob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDob)->format('Y-m-d');
                            } else {
                                $parsedDob = \Carbon\Carbon::parse($excelDob)->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $parsedDob = null;
                        }
                    }
                    
                    // Standardize status strings
                    $mappedStatus = '';
                    $lowerExcelStatus = strtolower($excelStatus);
                    if ($lowerExcelStatus === 'pass' || $lowerExcelStatus === 'passed') {
                        $mappedStatus = 'Passed';
                    } elseif ($lowerExcelStatus === 'fail' || $lowerExcelStatus === 'failed') {
                        $mappedStatus = 'Failed';
                    } elseif ($lowerExcelStatus === 'debar') {
                        $mappedStatus = 'Debar';
                    } elseif ($lowerExcelStatus === 'withheld') {
                        $mappedStatus = 'Withheld';
                    }

                    ExamResult::updateOrCreate(
                        ['reg_no' => $regNo],
                        [
                            'batch' => $sheetName,
                            'name' => $name,
                            'dob' => $parsedDob,
                            'marks_data' => $marksData,
                            'total_marks' => $excelTotalMarks !== '' ? $excelTotalMarks : $totalPossibleMarks,
                            'total_obt_marks' => $excelTotalObt !== '' ? $excelTotalObt : $calculatedTotalObt,
                            'daiya_rank' => $excelDaiyaRank !== '' ? $excelDaiyaRank : null,
                            'college_rank' => $excelCollegeRank !== '' ? $excelCollegeRank : null,
                            'status' => $mappedStatus !== '' ? $mappedStatus : ($passedAll ? 'Passed' : 'Failed'),
                        ]
                    );
                }

                // Auto-calculate Daiya rank (overall within batch) if empty
                $batchResults = ExamResult::where('batch', $sheetName)
                                          ->orderByRaw('CAST(total_obt_marks AS DECIMAL(10,2)) DESC')
                                          ->get();
                
                $daiyaRank = 1;
                foreach ($batchResults as $result) {
                    $status = strtoupper($result->status);
                    if ($status !== 'PASSED') {
                        if (empty($result->daiya_rank) || $result->daiya_rank !== 'Not Eligible') {
                            $result->update(['daiya_rank' => 'Not Eligible']);
                        }
                    } else {
                        if (empty($result->daiya_rank) || $result->daiya_rank === 'Not Eligible') {
                            $result->update(['daiya_rank' => (string)$daiyaRank]);
                        }
                        $daiyaRank++;
                    }
                }

                // Auto-calculate College rank (within branch, based on first two chars of reg_no) if empty
                $branchedResults = $batchResults->groupBy(function($item) {
                    return substr($item->reg_no, 0, 2);
                });

                foreach ($branchedResults as $branchCode => $studentsInBranch) {
                    $collegeRank = 1;
                    foreach ($studentsInBranch as $result) {
                        $status = strtoupper($result->status);
                        if ($status !== 'PASSED') {
                            if (empty($result->college_rank) || $result->college_rank !== 'Not Eligible') {
                                $result->update(['college_rank' => 'Not Eligible']);
                            }
                        } else {
                            if (empty($result->college_rank) || $result->college_rank === 'Not Eligible') {
                                $result->update(['college_rank' => (string)$collegeRank]);
                            }
                            $collegeRank++;
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Excel file imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
