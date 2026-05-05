<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;

class ResultManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamResult::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('reg_no', 'like', "%{$search}%")
                  ->orWhere('batch', 'like', "%{$search}%");
        }

        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        $results = $query->orderBy('batch')->orderByRaw('CAST(daiya_rank AS UNSIGNED) ASC')->paginate(15);
        
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');

        return view('admin.results.index', compact('results', 'batches'));
    }

    public function edit(ExamResult $result)
    {
        return view('admin.results.edit', compact('result'));
    }

    public function update(Request $request, ExamResult $result)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reg_no' => 'required|string|max:100',
            'batch' => 'required|string|max:100',
            'marks_data' => 'nullable|array',
            'status' => 'required|string'
        ]);

        $marksData = $request->input('marks_data', []);
        
        // Sanitize marks data
        $calculatedTotalObt = 0;
        $passedAll = true;
        
        foreach ($marksData as $subject => &$marks) {
            $subTotal = 0;
            if (isset($marks['TE']) && $marks['TE'] !== '') {
                $subTotal += (float) $marks['TE'];
            }
            if (isset($marks['CE']) && $marks['CE'] !== '') {
                $subTotal += (float) $marks['CE'];
            }
            $calculatedTotalObt += $subTotal;
            if ($subTotal > 0 && $subTotal < 35) {
                $passedAll = false;
            }
        }
        
        $totalPossibleMarks = count($marksData) * 100;
        
        $result->update([
            'name' => $request->name,
            'reg_no' => $request->reg_no,
            'batch' => $request->batch,
            'marks_data' => $marksData,
            'total_obt_marks' => $calculatedTotalObt,
            'total_marks' => $totalPossibleMarks,
            'status' => $request->status,
            'daiya_rank' => $request->daiya_rank,
            'college_rank' => $request->college_rank,
        ]);

        return redirect()->route('results.index')->with('success', 'Student record updated successfully.');
    }

    public function destroy(ExamResult $result)
    {
        $result->delete();
        return redirect()->route('results.index')->with('success', 'Student record deleted successfully.');
    }
}
