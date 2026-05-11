<?php

namespace App\Console\Commands;

use App\Mail\MotivationalReminderMail;
use App\Models\EmailTemplate;
use App\Models\Patient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyMealPlanReminders extends Command
{
    protected $signature   = 'reminders:weekly-meal-plan';
    protected $description = 'Send motivational reminder emails to opted-in patients, respecting each dietician\'s configured send day/hour.';

    public function handle(): int
    {
        $nowDay  = (int) now()->dayOfWeek; // 0 = Sunday … 6 = Saturday
        $nowHour = (int) now()->hour;

        $variant = now()->isoWeek() % 2 === 1 ? 1 : 2;
        $this->line("Checking reminders for day={$nowDay} hour={$nowHour} (ISO week " . now()->isoWeek() . ", variant {$variant})…");

        $patients = Patient::where('weekly_reminder_enabled', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->with(['user.pricingPackage'])
            ->get();

        $sent    = 0;
        $skipped = 0;

        // Cache templates per dietician to avoid N+1 queries
        $templateCache = [];

        foreach ($patients as $patient) {
            $dietician = $patient->user;

            if (! $dietician || ! $dietician->canAccessPlan('package_1')) {
                $skipped++;
                continue;
            }

            // Check if this dietician's configured send window matches now
            $sendDay  = (int) ($dietician->reminder_send_day  ?? 1); // default Monday
            $sendHour = (int) ($dietician->reminder_send_hour ?? 8); // default 08:00

            if ($nowDay !== $sendDay || $nowHour !== $sendHour) {
                $skipped++;
                continue;
            }

            // Load this dietician's custom template (once per dietician)
            if (! array_key_exists($dietician->id, $templateCache)) {
                $templateCache[$dietician->id] = EmailTemplate::where('user_id', $dietician->id)
                    ->where('type', 'motivational_reminder')
                    ->first();
            }
            $template = $templateCache[$dietician->id];

            try {
                Mail::to($patient->email, $patient->full_name)
                    ->send(new MotivationalReminderMail($patient, $dietician, $variant, $template));
                $sent++;
                $this->line("  Sent (variant {$variant}) → {$patient->full_name} <{$patient->email}>");
            } catch (\Throwable $e) {
                $this->error("  Failed → {$patient->full_name}: {$e->getMessage()}");
            }
        }

        $this->info("Reminders done: {$sent} sent, {$skipped} skipped.");

        return self::SUCCESS;
    }
}


