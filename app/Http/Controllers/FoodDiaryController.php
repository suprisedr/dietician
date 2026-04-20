<?php

namespace App\Http\Controllers;

use App\Models\FoodDiary;
use App\Models\Patient;
use App\Mail\FoodDiaryInviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FoodDiaryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));

        $query = FoodDiary::where('user_id', auth()->id())
            ->with('patient')
            ->orderByDesc('diary_date');

        if ($search !== '') {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('diary_date', 'like', "%{$search}%");
        }

        $diaries = $query->paginate(20)->withQueryString();

        return view('food-diary.index', compact('diaries', 'search'));
    }

    public function create()
    {
        $patients = Patient::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name', 'surname']);
        return view('food-diary.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'  => 'nullable|exists:patients,id',
            'diary_date'  => 'required|date',
            'breakfast'   => 'nullable|string|max:2000',
            'snack1'      => 'nullable|string|max:2000',
            'lunch'       => 'nullable|string|max:2000',
            'snack2'      => 'nullable|string|max:2000',
            'supper'      => 'nullable|string|max:2000',
            'snack3'      => 'nullable|string|max:2000',
            'rating'      => 'nullable|integer|min:1|max:5',
            'improvement' => 'nullable|string|max:3000',
        ]);

        // Ensure the patient belongs to this user
        if (!empty($data['patient_id'])) {
            abort_if(
                !Patient::where('id', $data['patient_id'])->where('user_id', auth()->id())->exists(),
                403
            );
        }

        $diary = FoodDiary::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('food-diary.show', $diary)
            ->with('success', 'Food diary entry saved.');
    }

    public function show(FoodDiary $foodDiary)
    {
        abort_if($foodDiary->user_id !== auth()->id(), 403);
        $foodDiary->load('patient');
        return view('food-diary.show', compact('foodDiary'));
    }

    public function edit(FoodDiary $foodDiary)
    {
        abort_if($foodDiary->user_id !== auth()->id(), 403);
        $patients = Patient::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name', 'surname']);
        return view('food-diary.edit', compact('foodDiary', 'patients'));
    }

    public function update(Request $request, FoodDiary $foodDiary)
    {
        abort_if($foodDiary->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'patient_id'  => 'nullable|exists:patients,id',
            'diary_date'  => 'required|date',
            'breakfast'   => 'nullable|string|max:2000',
            'snack1'      => 'nullable|string|max:2000',
            'lunch'       => 'nullable|string|max:2000',
            'snack2'      => 'nullable|string|max:2000',
            'supper'      => 'nullable|string|max:2000',
            'snack3'      => 'nullable|string|max:2000',
            'rating'      => 'nullable|integer|min:1|max:5',
            'improvement' => 'nullable|string|max:3000',
        ]);

        if (!empty($data['patient_id'])) {
            abort_if(
                !Patient::where('id', $data['patient_id'])->where('user_id', auth()->id())->exists(),
                403
            );
        }

        $foodDiary->update($data);

        return redirect()->route('food-diary.show', $foodDiary)
            ->with('success', 'Food diary updated.');
    }

    public function destroy(FoodDiary $foodDiary)
    {
        abort_if($foodDiary->user_id !== auth()->id(), 403);
        $foodDiary->delete();
        return redirect()->route('food-diary.index')
            ->with('success', 'Diary entry deleted.');
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patient = Patient::where('id', $request->patient_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if(empty($patient->email), 422, 'This patient has no email address on file.');

        // Create a pending diary record with a unique token
        $diary = FoodDiary::create([
            'user_id'       => auth()->id(),
            'patient_id'    => $patient->id,
            'patient_token' => Str::random(48),
        ]);

        Mail::to($patient->email, $patient->name)
            ->send(new FoodDiaryInviteMail($diary, auth()->user()));

        return back()->with('success', 'Food diary invitation sent to ' . $patient->email . '.');
    }

    public function pdf(FoodDiary $foodDiary)
    {
        abort_if($foodDiary->user_id !== auth()->id(), 403);
        $foodDiary->load('patient');

        $letterhead = auth()->user()->letterheadBase64();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('food-diary.pdf', compact('foodDiary', 'letterhead'))
            ->setPaper('a4', 'portrait');

        $date     = $foodDiary->diary_date->format('Y-m-d');
        $patient  = $foodDiary->patient;
        $nameSlug = $patient ? \Illuminate\Support\Str::slug($patient->name) : 'diary';
        $filename = 'food-diary-' . $nameSlug . '-' . $date . '.pdf';

        return $pdf->download($filename);
    }
}
