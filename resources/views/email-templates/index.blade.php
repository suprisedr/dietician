<x-app-layout>

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        Communications
                    </p>
                    <h1>Email Templates</h1>
                    <p>Customise the reminder emails sent to your patients — like your own newsletter.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Flash messages --}}
        @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #86efac;border-left:4px solid #16a34a;border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.88rem;color:#166534;display:flex;align-items:center;gap:.6rem">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Intro --}}
        <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:.85rem">
            <span style="font-size:1.4rem;flex-shrink:0;line-height:1">✉️</span>
            <div style="font-size:.88rem;color:var(--text-muted);line-height:1.65">
                <p style="margin:0 0 .35rem"><strong style="color:var(--text-primary)">Newsletter-style email personalisation.</strong> Each template below is sent automatically to your opted-in patients on a schedule. Customise the subject, heading, and body text — and use merge tags like <code style="background:#f1f5f9;border-radius:4px;padding:.1rem .3rem;font-size:.82rem">{patient_name}</code> to personalise every send.</p>
                <p style="margin:0">Unsaved templates fall back to the built-in defaults automatically.</p>
            </div>
        </div>

        {{-- Template cards --}}
        @php
            $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            $currentDay  = auth()->user()->reminder_send_day  ?? 1;
            $currentHour = auth()->user()->reminder_send_hour ?? 8;
        @endphp
        <div class="grid gap-5">
            @foreach(App\Models\EmailTemplate::meta() as $type => $info)
            @php $tpl = $templates->get($type); $isCustom = $tpl && ($tpl->subject || $tpl->heading || $tpl->body_html); @endphp
            <div style="background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.04)">
                <div style="padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:2rem;line-height:1;flex-shrink:0">{{ $info['icon'] }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap">
                            <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">{{ $info['label'] }}</h3>
                            @if($isCustom)
                            <span style="display:inline-flex;align-items:center;gap:.3rem;background:#dcfce7;border:1px solid #86efac;border-radius:999px;padding:.15rem .6rem;font-size:.68rem;font-weight:700;color:#15803d;letter-spacing:.03em;text-transform:uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.65rem;height:.65rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Customised
                            </span>
                            @else
                            <span style="display:inline-flex;align-items:center;gap:.3rem;background:#f1f5f9;border:1px solid #cbd5e1;border-radius:999px;padding:.15rem .6rem;font-size:.68rem;font-weight:700;color:#64748b;letter-spacing:.03em;text-transform:uppercase">Using Default</span>
                            @endif
                        </div>
                        <p style="font-size:.82rem;color:var(--text-muted);margin:.2rem 0 0">{{ $info['description'] }}</p>
                    </div>
                    <div style="display:flex;gap:.5rem;flex-shrink:0">
                        <a href="{{ route('email-templates.preview', $type) }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;border:1.5px solid var(--border);color:var(--text-muted);transition:all .15s"
                           title="Preview">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview
                        </a>
                        <a href="{{ route('email-templates.edit', $type) }}"
                           style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:8px;font-size:.8rem;font-weight:700;text-decoration:none;background:var(--primary-dark);color:#fff;transition:all .15s">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Template
                        </a>
                    </div>
                </div>
                {{-- Info strip --}}
                <div style="padding:.6rem 1.5rem;background:#fafbfc;display:flex;align-items:center;gap:1.5rem;font-size:.76rem;color:var(--text-muted)">
                    <span style="display:flex;align-items:center;gap:.35rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.8rem;height:.8rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $days[$currentDay] }} at {{ str_pad($currentHour, 2, '0', STR_PAD_LEFT) }}:00
                    </span>
                    @if($tpl)
                    <span style="display:flex;align-items:center;gap:.35rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.8rem;height:.8rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Last edited {{ $tpl->updated_at->diffForHumans() }}
                    </span>
                    @endif
                    @if($tpl && $tpl->subject)
                    <span style="display:flex;align-items:center;gap:.35rem;flex:1;min-width:0;overflow:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.8rem;height:.8rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Subject: <em style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $tpl->subject }}</em>
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Send Schedule ─────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('email-templates.schedule.update') }}" class="mt-5">
            @csrf
            @method('PATCH')
            <div style="background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.04)">
                <div style="padding:1.1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.75rem">
                    <span style="font-size:1.5rem;line-height:1">⏰</span>
                    <div>
                        <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">Send Schedule</h3>
                        <p style="font-size:.82rem;color:var(--text-muted);margin:.15rem 0 0">Choose the day and time your patients receive their reminder emails each week.</p>
                    </div>
                </div>
                <div style="padding:1.25rem 1.5rem;display:flex;align-items:flex-end;gap:1rem;flex-wrap:wrap">
                    {{-- Day picker --}}
                    <div style="flex:1;min-width:160px">
                        <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">Day of week</label>
                        <div style="position:relative">
                            <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);width:.85rem;height:.85rem;color:var(--text-muted);pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <select name="reminder_send_day"
                                    style="width:100%;padding:.6rem .85rem .6rem 2.1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);background:#fff;appearance:none;outline:none;cursor:pointer;transition:border-color .15s"
                                    onfocus="this.style.borderColor='var(--primary-dark)'"
                                    onblur="this.style.borderColor='var(--border)'">
                                @foreach($days as $i => $day)
                                <option value="{{ $i }}" @selected($currentDay === $i)>{{ $day }}</option>
                                @endforeach
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;right:.7rem;top:50%;transform:translateY(-50%);width:.8rem;height:.8rem;color:var(--text-muted);pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Hour picker --}}
                    <div style="flex:1;min-width:160px">
                        <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">Time</label>
                        <div style="position:relative">
                            <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);width:.85rem;height:.85rem;color:var(--text-muted);pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <select name="reminder_send_hour"
                                    style="width:100%;padding:.6rem .85rem .6rem 2.1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);background:#fff;appearance:none;outline:none;cursor:pointer;transition:border-color .15s"
                                    onfocus="this.style.borderColor='var(--primary-dark)'"
                                    onblur="this.style.borderColor='var(--border)'">
                                @for($h = 0; $h < 24; $h++)
                                <option value="{{ $h }}" @selected($currentHour === $h)>
                                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                                    @if($h < 12) ({{ $h === 0 ? '12 AM' : $h . ' AM' }})
                                    @elseif($h === 12) (12 PM)
                                    @else ({{ $h - 12 }} PM)
                                    @endif
                                </option>
                                @endfor
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;right:.7rem;top:50%;transform:translateY(-50%);width:.8rem;height:.8rem;color:var(--text-muted);pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.4rem;background:var(--primary-dark);color:#fff;border:none;border-radius:9px;font-size:.88rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:opacity .15s"
                            onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Schedule
                    </button>
                </div>
                <div style="padding:.6rem 1.5rem;background:#fafbfc;font-size:.76rem;color:var(--text-muted);border-top:1px solid var(--border)">
                    Emails are dispatched at the top of the selected hour. All patients with reminders enabled will receive their email at this time.
                </div>
            </div>
        </form>

        {{-- Merge tags reference --}}
        <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:1.25rem 1.5rem;margin-top:1.5rem">
            <h4 style="font-size:.85rem;font-weight:700;color:var(--text-primary);margin:0 0 .75rem;display:flex;align-items:center;gap:.5rem">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.95rem;height:.95rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                Available Merge Tags
            </h4>
            <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                @foreach(['{patient_name}' => "Patient's first name", '{patient_full_name}' => "Patient's full name with title", '{dietician_name}' => "Your name"] as $tag => $desc)
                <div style="display:flex;align-items:center;gap:.4rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:.3rem .65rem">
                    <code style="font-size:.78rem;font-weight:700;color:var(--primary-dark)">{{ $tag }}</code>
                    <span style="font-size:.72rem;color:var(--text-muted)">— {{ $desc }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
