<?php

namespace App\Console\Commands;

use App\Mail\WeeklyMealPlanReminderMail;
use App\Models\Patient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyMealPlanReminders extends Command
{
    protected $signature   = 'reminders:weekly-meal-plan';
    protected $description = 'Send weekly meal plan reminder emails to opted-in patients whose dietician has an active plan.';

    public function handle(): int
    {
        $patients = Patient::where('weekly_reminder_enabled', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->with(['user.pricingPackage', 'mealPlannerWeeks.entries.mealItem'])
            ->get();

        $sent   = 0;
        $skipped = 0;

        foreach ($patients as $patient) {
            $dietician = $patient->user;

            // Only send if the dietician has package_1 or higher (meal plans are a package_1 feature)
            if (! $dietician || ! $dietician->canAccessPlan('package_1')) {
                $skipped++;
                continue;
            }

            // Most recent meal plan week for this patient
            $latestWeek = $patient->mealPlannerWeeks->first();

            try {
                Mail::to($patient->email, $patient->full_name)
                    ->send(new WeeklyMealPlanReminderMail($patient, $dietician, $latestWeek));
                $sent++;
                $this->line("  Sent → {$patient->full_name} <{$patient->email}>");
            } catch (\Throwable $e) {
                $this->error("  Failed → {$patient->full_name}: {$e->getMessage()}");
            }
        }

        $this->info("Weekly reminders: {$sent} sent, {$skipped} skipped (no plan access).");

        return self::SUCCESS;
    }
}
