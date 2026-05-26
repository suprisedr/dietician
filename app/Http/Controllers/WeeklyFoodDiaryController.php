<?php

namespace App\Http\Controllers;

use App\Models\FoodDiary;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeeklyFoodDiaryController extends Controller
{
    /** Parse and clamp the requested week to a Monday. */
    private function resolveWeek(Request $request): Carbon
    {
        $raw = $request->input('week');
        if ($raw && preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return Carbon::parse($raw)->startOfWeek(Carbon::MONDAY);
        }
        return Carbon::now()->startOfWeek(Carbon::MONDAY);
    }

    /** Build the 7-element weekDays array for a patient + weekStart. */
    private function buildWeekDays(int $patientId, Carbon $weekStart): array
    {
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $diaries = FoodDiary::where('user_id', auth()->id())
            ->where('patient_id', $patientId)
            ->whereBetween('diary_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(fn($d) => $d->diary_date->format('Y-m-d'));

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $weekDays[] = [
                'date'  => $day,
                'diary' => $diaries[$day->format('Y-m-d')] ?? null,
            ];
        }

        return $weekDays;
    }

    public function show(Request $request)
    {
        $request->validate([
            'patient_id' => 'nullable|integer|exists:patients,id',
            'week'       => 'nullable|date',
        ]);

        $patients  = Patient::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name', 'surname']);
        $patientId = (int) $request->input('patient_id', 0) ?: null;
        $patient   = null;
        $weekDays  = [];
        $weekStart = $this->resolveWeek($request);

        if ($patientId) {
            $patient = Patient::where('id', $patientId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $weekDays = $this->buildWeekDays($patientId, $weekStart);
        }

        return view('food-diary.weekly-show', compact(
            'patients', 'patient', 'patientId', 'weekStart', 'weekDays'
        ));
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'week'       => 'nullable|date',
        ]);

        $patient = Patient::where('id', $request->patient_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $weekStart = $this->resolveWeek($request);
        $weekDays  = $this->buildWeekDays($patient->id, $weekStart);
        $letterhead = auth()->user()->letterheadBase64();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'food-diary.weekly-pdf',
            compact('patient', 'weekStart', 'weekDays', 'letterhead')
        )->setPaper('a4', 'portrait');

        $filename = 'weekly-diary-'
            . Str::slug($patient->name)
            . '-' . $weekStart->format('Y-m-d')
            . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }
}
