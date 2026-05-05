<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;

class ResultController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function search(Request $request)
    {
        $request->validate([
            'reg_no' => 'required|string|max:50'
        ]);

        $result = ExamResult::where('reg_no', trim($request->reg_no))->first();

        if (!$result) {
            return redirect()->back()->with('error', 'No result found for the given Registration Number.');
        }

        return view('result', compact('result'));
    }
}
