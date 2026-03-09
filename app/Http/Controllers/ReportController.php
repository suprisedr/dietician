<?php

namespace App\Http\Controllers;

use App\Models\Patient;

class ReportController extends Controller
{
    public function index()
    {
        $patients = Patient::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('reports.index', compact('patients'));
    }
}
