<?php

namespace App\Http\Controllers;

use App\Mail\MotivationalReminderMail;
use App\Mail\WeeklyMealPlanReminderMail;
use App\Models\EmailTemplate;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    private const TYPES = [
        'motivational_reminder',
        'weekly_meal_plan_reminder',
    ];

    public function index()
    {
        $templates = EmailTemplate::where('user_id', auth()->id())
            ->get()
            ->keyBy('type');

        return view('email-templates.index', compact('templates'));
    }

    public function edit(string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $template = EmailTemplate::firstOrNew(
            ['user_id' => auth()->id(), 'type' => $type]
        );

        $meta = EmailTemplate::meta()[$type];

        return view('email-templates.edit', compact('template', 'type', 'meta'));
    }

    public function update(Request $request, string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $data = $request->validate([
            'subject'   => ['nullable', 'string', 'max:255'],
            'heading'   => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'cta_text'  => ['nullable', 'string', 'max:120'],
            'cta_url'   => ['nullable', 'url', 'max:500'],
        ]);

        EmailTemplate::updateOrCreate(
            ['user_id' => auth()->id(), 'type' => $type],
            $data,
        );

        return back()->with('success', 'Email template saved successfully.');
    }

    public function preview(string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $template  = EmailTemplate::where('user_id', auth()->id())->where('type', $type)->first();
        $dietician = auth()->user();

        // Use a representative dummy patient for preview
        $patient = new Patient([
            'title'   => 'Ms',
            'name'    => 'Jane',
            'surname' => 'Preview',
            'email'   => $dietician->email,
        ]);

        $mailable = match ($type) {
            'motivational_reminder'     => new MotivationalReminderMail($patient, $dietician, 1, $template),
            'weekly_meal_plan_reminder' => new WeeklyMealPlanReminderMail($patient, $dietician, null, $template),
        };

        return response($mailable->render())
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Security-Policy', "default-src 'none'; style-src 'unsafe-inline'; img-src data: https:;");
    }

    public function updateSchedule(Request $request)
    {
        $data = $request->validate([
            'reminder_send_day'  => ['required', 'integer', 'min:0', 'max:6'],
            'reminder_send_hour' => ['required', 'integer', 'min:0', 'max:23'],
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Reminder schedule saved.');
    }

    public function sendTest(string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $dietician = auth()->user();
        $template  = EmailTemplate::where('user_id', $dietician->id)->where('type', $type)->first();

        $patient = new Patient([
            'title'   => 'Ms',
            'name'    => 'Jane',
            'surname' => 'Preview',
            'email'   => $dietician->email,
        ]);

        $mailable = match ($type) {
            'motivational_reminder'     => new MotivationalReminderMail($patient, $dietician, 1, $template),
            'weekly_meal_plan_reminder' => new WeeklyMealPlanReminderMail($patient, $dietician, null, $template),
        };

        Mail::to($dietician->email, $dietician->name)->send($mailable);

        return back()->with('success', "Test email sent to {$dietician->email}.");
    }
}
