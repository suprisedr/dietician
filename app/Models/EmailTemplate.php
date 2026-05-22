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
            'health_journey_welcome' => [
                'label'           => 'Health Journey Welcome',
                'description'     => 'Sent to patients when they start their health journey.',
                'icon'            => '🌟',
                'schedule'        => 'On enrollment',
                'default_subject' => 'Your Health Journey Starts Here',
                'default_heading' => 'Your Health Journey Starts Here — You\'ve Got This!',
                'default_body'    => "<p>Congratulations on taking this important first step toward prioritizing your health through structured meal planning. Making the decision to commit to nourishing your body is a powerful one and I want to commend you for choosing to invest in yourself.</p>\n<p>Starting a new eating plan can feel overwhelming at first and that is completely normal.</p>\n<p>Please remember: this journey is not about perfection, but about progress. Each balanced meal, each mindful choice and each day you follow your plan is a step toward more energy, improved wellbeing, and long-term health.</p>\n<p><strong>What you can look forward to:</strong></p>\n<ul>\n<li>Increased energy levels to get through your day with ease</li>\n<li>Improved focus and mood as your body receives consistent, quality nutrition</li>\n<li>A sense of control and confidence that comes from fueling your body intentionally</li>\n<li>Celebrating non-scale victories like better sleep, clearer skin, or looser clothing</li>\n</ul>\n<p>There will be days that are easier than others. If you have a meal that is not on the plan, view it as one moment, not a failure. Simply resume with your next planned meal. Consistency over time is what creates lasting results not rigid perfection.</p>\n<p><strong>My tips for success:</strong></p>\n<ul>\n<li><strong>Plan and prep:</strong> Set aside time each week to prepare. Future you will be grateful.</li>\n<li><strong>Stay curious:</strong> Notice how different foods make you feel. You're learning what works best for your body.</li>\n<li><strong>Reach out:</strong> If you have questions or feel stuck, I am here to support and adjust the plan with you. You do not have to do this alone.</li>\n</ul>\n<p>You have already done the hardest part by starting. I am confident in your ability to succeed and I am here to guide you every step of the way.</p>\n<p>Warm regards,<br><strong>{dietician_name}</strong></p>",
            ],
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
