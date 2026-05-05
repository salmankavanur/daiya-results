<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchSubject;
use App\Models\ExamResult;

class BatchSubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = BatchSubject::query();
        
        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        $subjects = $query->orderBy('batch')->orderBy('name')->get();
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');
        
        // If there are no results yet, the admin can manually type batch names in create mode,
        // but let's also fetch from BatchSubject
        $existingBatches = BatchSubject::select('batch')->distinct()->pluck('batch');
        $allBatches = $batches->merge($existingBatches)->unique()->sort();

        return view('admin.subjects.index', compact('subjects', 'allBatches'));
    }

    public function create()
    {
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');
        $existingBatches = BatchSubject::select('batch')->distinct()->pluck('batch');
        $allBatches = $batches->merge($existingBatches)->unique()->sort();
        
        return view('admin.subjects.create', compact('allBatches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'max_te' => 'required|integer|min:0',
            'max_ce' => 'required|integer|min:0',
            'pass_mark' => 'required|integer|min:0',
        ]);

        BatchSubject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject configuration added successfully.');
    }

    public function edit(BatchSubject $subject)
    {
        $batches = ExamResult::select('batch')->distinct()->pluck('batch');
        $existingBatches = BatchSubject::select('batch')->distinct()->pluck('batch');
        $allBatches = $batches->merge($existingBatches)->unique()->sort();

        return view('admin.subjects.edit', compact('subject', 'allBatches'));
    }

    public function update(Request $request, BatchSubject $subject)
    {
        $request->validate([
            'batch' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'max_te' => 'required|integer|min:0',
            'max_ce' => 'required|integer|min:0',
            'pass_mark' => 'required|integer|min:0',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject configuration updated successfully.');
    }

    public function destroy(BatchSubject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject configuration deleted successfully.');
    }
}
