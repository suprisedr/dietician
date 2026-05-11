<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Weekly Meal Plan Reminder</title>
<style>
    body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
    body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; color:#1e293b; }
    .wrap { max-width:600px; margin:0 auto; }

    .hd { background:linear-gradient(135deg,#1a3d2b 0%,#2d5a43 55%,#4a7a60 100%); border-radius:14px 14px 0 0; }
    .hd-inner { padding:2.2rem 2rem 1.8rem; text-align:center; }
    .hd-icon { font-size:2.5rem; display:block; margin-bottom:.6rem; }
    .hd h1 { color:#fff; font-size:1.35rem; font-weight:800; margin:0 0 .3rem; font-family:'Segoe UI',Arial,sans-serif; }
    .hd p  { color:rgba(255,255,255,.8); font-size:.88rem; margin:0; }

    .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.55rem 2rem; text-align:center; font-size:.7rem; font-weight:700; color:#2d5a43; letter-spacing:.09em; text-transform:uppercase; }

    .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
    .bd p { font-size:.92rem; color:#2e3d30; line-height:1.8; margin:0 0 1rem; }
    .bd strong { color:#1a3d2b; }
    .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }

    .info-box { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #2d5a43; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.9rem; color:#1e4030; margin:1.2rem 0; line-height:1.65; }

    /* Meal plan summary table */
    .plan-table { width:100%; border-collapse:collapse; margin:1.2rem 0; font-size:.82rem; }
    .plan-table th { background:#2d5a43; color:#fff; padding:.45rem .7rem; text-align:left; font-weight:700; letter-spacing:.04em; text-transform:uppercase; font-size:.72rem; }
    .plan-table td { padding:.5rem .7rem; border-bottom:1px solid #e6f2ec; color:#2e3d30; vertical-align:top; }
    .plan-table tr:last-child td { border-bottom:none; }
    .plan-table tr:nth-child(even) td { background:#f8fcfa; }
    .day-label { font-weight:700; color:#1a3d2b; white-space:nowrap; }
    .slot-tag { display:inline-block; font-size:.68rem; font-weight:700; color:#4a7a60; background:#e8f5ec; border-radius:3px; padding:.1rem .35rem; margin-right:.3rem; text-transform:uppercase; letter-spacing:.03em; }
    .no-plan { background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.88rem; color:#92400e; margin:1.2rem 0; }

    .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
    .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    <div class="hd">
        <div class="hd-inner">
            <span class="hd-icon">&#x1F96C;</span>
            <h1>{{ $template?->heading ?: 'Your Weekly Meal Plan' }}</h1>
            <p>Sent by {{ $dietician->name }} &mdash; {{ config('app.name') }}</p>
        </div>
    </div>

    <div class="badge-strip">&#x2665; Personalised Nutrition Plan</div>

    <div class="bd">
        @php $tplVars = ['patient_name' => $patient->name, 'patient_full_name' => $patient->full_name, 'dietician_name' => $dietician->name]; @endphp

        @if($template?->body_html)
            {!! $template->resolveBody($tplVars) !!}
        @else
        <p>Hi <strong>{{ $patient->full_name }}</strong>,</p>

        <p>
            Here is a reminder from your dietician <strong>{{ $dietician->name }}</strong> about your
            meal plan for the week. Sticking to your plan consistently is the key to reaching your
            nutrition goals &mdash; you&rsquo;ve got this!
        </p>
        @endif

        <hr class="divider">

        @if($week)
            <div class="info-box">
                &#x1F4C5; <strong>Week of {{ $week->week_start->format('d M Y') }}</strong>
                @if($week->label)
                    &mdash; {{ $week->label }}
                @endif
            </div>

            @php
                $days      = \App\Models\MealPlannerWeek::DAYS;
                $slotLabels = \App\Models\MealPlannerWeek::SLOT_LABELS;
                $grouped   = $week->entries->groupBy('day_of_week');
            @endphp

            @if($grouped->isNotEmpty())
            <table class="plan-table">
                <thead>
                    <tr>
                        <th style="width:100px">Day</th>
                        <th>Meals</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $dayIndex => $dayName)
                        @php $dayEntries = $grouped->get($dayIndex, collect()); @endphp
                        @if($dayEntries->isNotEmpty())
                        <tr>
                            <td class="day-label">{{ $dayName }}</td>
                            <td>
                                @foreach($dayEntries->groupBy('meal_slot') as $slot => $entries)
                                    <span class="slot-tag">{{ $slotLabels[$slot] ?? $slot }}</span>
                                    @foreach($entries as $entry)
                                        {{ $entry->mealItem?->name ?? $entry->meal_text }}@if(!$loop->last), @endif
                                    @endforeach
                                    <br>
                                @endforeach
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-plan">
                &#x26A0; No meal entries have been added to this week&rsquo;s plan yet. Your dietician will update it soon.
            </div>
            @endif
        @else
            <div class="no-plan">
                &#x26A0; No meal plan has been set up for you yet. Your dietician will create one shortly &mdash; stay tuned!
            </div>
        @endif

        <hr class="divider">

        <div class="info-box">
            &#x1F4A7; <strong>Hydration reminder:</strong> Aim for at least 8 glasses of water per day.
            Staying hydrated supports your metabolism and keeps energy levels stable throughout the day.
        </div>

        @if($template?->cta_text && $template?->cta_url)
        <div class="cta-wrap" style="margin-top:1.2rem">
            <a href="{{ $template->cta_url }}" style="display:inline-block;background:linear-gradient(135deg,#2d5a43,#4a7a60);color:#fff;text-decoration:none;padding:.75rem 2rem;border-radius:8px;font-size:.9rem;font-weight:700;letter-spacing:.02em">{{ $template->cta_text }}</a>
        </div>
        @endif

        <p style="font-size:.83rem;color:#64748b;margin-top:1rem">
            If you have questions about your meal plan, please contact your dietician&nbsp;<strong>{{ $dietician->name }}</strong> directly.
        </p>
    </div>

    <div class="ft">
        <p>
            You are receiving this because your dietician enabled weekly reminders for you.<br>
            &copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.
        </p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
