<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'heading',
        'body_html',
        'cta_text',
        'cta_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Replace {merge_tag} placeholders with actual values.
     * Tags: {patient_name}, {patient_full_name}, {dietician_name}
     */
    public function resolveBody(array $vars): string
    {
        $html = $this->body_html ?? '';
        foreach ($vars as $key => $value) {
            $html = str_replace('{' . $key . '}', e($value), $html);
        }
        return $html;
    }

    public function resolveSubject(array $vars): string
    {
        $subject = $this->subject ?? '';
        foreach ($vars as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
        }
        return $subject;
    }

    /**
     * Metadata for each supported template type.
     */
    public static function meta(): array
    {
        return [
            'motivational_reminder' => [
                'label'           => 'Motivational Reminder',
                'description'     => 'Sent weekly to patients who have motivational reminders enabled.',
                'icon'            => '💪',
                'schedule'        => 'Every Monday at 08:00',
                'default_subject' => 'Keep Going — Your Meal Plan Is Working',
                'default_heading' => 'Keep Going — Your Plan Is Working!',
                'default_body'    => "<p>Hi <strong>{patient_name}</strong>,</p>\n<p>Another week, another opportunity to nourish your body and move closer to your goals. Every consistent choice adds up to lasting results — keep going!</p>\n<p>If you have any questions about your meal plan, feel free to reach out to me directly.</p>\n<p>Warm regards,<br><strong>{dietician_name}</strong></p>",
            ],
            'weekly_meal_plan_reminder' => [
                'label'           => 'Weekly Meal Plan Reminder',
                'description'     => 'Sent weekly to remind patients about their upcoming meal plan.',
                'icon'            => '🥗',
                'schedule'        => 'Every Monday at 08:00',
                'default_subject' => 'Your Weekly Meal Plan Reminder',
                'default_heading' => 'Your Weekly Meal Plan',
                'default_body'    => "<p>Hi <strong>{patient_name}</strong>,</p>\n<p>Here is a reminder from <strong>{dietician_name}</strong> about your meal plan for the week. Sticking to your plan consistently is the key to reaching your nutrition goals — you've got this!</p>",
            ],
        ];
    }
}
