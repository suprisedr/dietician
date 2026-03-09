<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);letter-spacing:-.025em;margin:0">
            Connected Devices
        </h2>
    </x-slot>

    <style>
        .dv-wrap   { max-width: 820px; margin: 2rem auto; padding: 0 1.25rem 4rem; }

        /* ── Header bar ── */
        .dv-header { display: flex; align-items: center; justify-content: space-between;
                     flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }
        .dv-title  { font-size: 1.25rem; font-weight: 800; color: var(--text-primary); letter-spacing: -.025em; }
        .dv-sub    { font-size: .85rem; color: var(--text-muted); margin-top: .2rem; }

        /* ── Quota bar ── */
        .dv-quota  { background: #fff; border: 1px solid var(--border,#d4e6d1);
                     border-radius: .75rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
                     display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .dv-quota-label { font-size: .8rem; font-weight: 700; color: var(--text-muted); flex: 1; min-width: 140px; }
        .dv-quota-bar   { flex: 2; min-width: 160px; background: #e8f5e6; border-radius: 999px; height: .55rem; overflow: hidden; }
        .dv-quota-fill  { height: 100%; border-radius: 999px;
                          background: linear-gradient(90deg, #679F5F, #429677);
                          transition: width .4s ease; }
        .dv-quota-fill.full { background: linear-gradient(90deg, #e05252, #c0392b); }
        .dv-quota-count { font-size: .85rem; font-weight: 800; color: var(--text-primary); white-space: nowrap; }

        /* ── Device card ── */
        .dv-list   { display: flex; flex-direction: column; gap: .85rem; }
        .dv-card   { background: #fff; border: 1px solid var(--border,#d4e6d1);
                     border-radius: .85rem; padding: 1.1rem 1.25rem;
                     display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
                     transition: box-shadow .15s; }
        .dv-card:hover { box-shadow: 0 4px 18px rgba(13,31,12,.08); }
        .dv-card.current { border-color: #679F5F; background: #f4fbf3; }

        .dv-icon   { width: 2.6rem; height: 2.6rem; border-radius: .65rem; flex-shrink: 0;
                     background: #e8f5e6; display: flex; align-items: center; justify-content: center; }
        .dv-icon svg { width: 1.3rem; height: 1.3rem; color: #4d7d47; }
        .dv-card.current .dv-icon { background: linear-gradient(135deg,#679F5F,#429677); }
        .dv-card.current .dv-icon svg { color: #fff; }

        .dv-info   { flex: 1; min-width: 0; }
        .dv-name   { font-size: .9rem; font-weight: 700; color: var(--text-primary); }
        .dv-meta   { font-size: .76rem; color: var(--text-muted); margin-top: .1rem; }

        .dv-badges { display: flex; gap: .4rem; align-items: center; flex-shrink: 0; flex-wrap: wrap; }
        .dv-badge-current { background: #e8f5e6; color: #4d7d47;
                            font-size: .68rem; font-weight: 800; text-transform: uppercase;
                            letter-spacing: .06em; padding: .2rem .65rem; border-radius: 999px; }
        .dv-revoke { display: inline-flex; align-items: center; gap: .3rem;
                     padding: .35rem .85rem; font-size: .78rem; font-weight: 700;
                     color: #e05252; border: 1.5px solid #fecaca; background: #fff;
                     border-radius: .5rem; cursor: pointer; text-decoration: none;
                     transition: all .15s; }
        .dv-revoke:hover { background: #fef2f2; border-color: #e05252; }

        /* ── Revoke all button ── */
        .dv-revoke-all { display: inline-flex; align-items: center; gap: .4rem;
                         padding: .45rem 1.1rem; font-size: .82rem; font-weight: 700;
                         color: #e05252; border: 1.5px solid #fecaca; background: #fff;
                         border-radius: .6rem; cursor: pointer; text-decoration: none;
                         transition: all .15s; }
        .dv-revoke-all:hover { background: #fef2f2; border-color: #e05252; }

        /* ── Flash messages ── */
        .dv-flash { padding: .7rem 1.1rem; border-radius: .6rem;
                    font-size: .85rem; font-weight: 600; margin-bottom: 1.25rem; }
        .dv-flash.success { background: #e8f5e6; color: #2e6e56; border: 1px solid #c3e6c0; }
        .dv-flash.error   { background: #fef2f2; color: #c0392b; border: 1px solid #fecaca; }

        /* ── Empty state ── */
        .dv-empty  { text-align: center; padding: 3rem 1rem; color: var(--text-muted); font-size: .9rem; }
    </style>

    <div class="dv-wrap">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="dv-flash success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="dv-flash error">{{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div class="dv-header">
            <div>
                <div class="dv-title">Connected Devices</div>
                <div class="dv-sub">Manage every browser session linked to your account.</div>
            </div>

            @if($devices->count() > 1)
                <form method="POST" action="{{ route('devices.revoke-others') }}"
                      onsubmit="return confirm('Sign out all other devices?')">
                    @csrf
                    <button type="submit" class="dv-revoke-all">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign out all other devices
                    </button>
                </form>
            @endif
        </div>

        {{-- Quota bar --}}
        @php
            $used     = $devices->count();
            $pct      = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 100;
            $isFull   = $used >= $limit;
            $pkg      = auth()->user()->pricingPackage;
        @endphp
        <div class="dv-quota">
            <div class="dv-quota-label">
                {{ $pkg?->name ?? 'Free' }} Plan &mdash; device slots
            </div>
            <div class="dv-quota-bar">
                <div class="dv-quota-fill {{ $isFull ? 'full' : '' }}"
                     style="width: {{ $pct }}%"></div>
            </div>
            <div class="dv-quota-count" style="{{ $isFull ? 'color:#e05252' : '' }}">
                {{ $used }} / {{ $limit }}
            </div>
        </div>

        {{-- Device list --}}
        @if($devices->isEmpty())
            <div class="dv-empty">No active device sessions found.</div>
        @else
            <div class="dv-list">
                @foreach($devices as $device)
                    <div class="dv-card {{ $device->is_current ? 'current' : '' }}">

                        {{-- Icon: desktop vs mobile --}}
                        <div class="dv-icon">
                            @if(in_array($device->platform, ['iPhone', 'iPad', 'Android']))
                                {{-- Mobile icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="7" y="2" width="10" height="20" rx="2" ry="2"/>
                                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                                </svg>
                            @else
                                {{-- Desktop / laptop icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8M12 17v4"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="dv-info">
                            <div class="dv-name">{{ $device->device_name }}</div>
                            <div class="dv-meta">
                                IP {{ $device->ip_address ?? '—' }}
                                &bull; Signed in {{ $device->logged_in_at?->format('d M Y, H:i') ?? '—' }}
                                &bull; Last active {{ $device->lastSeenLabel() }}
                            </div>
                        </div>

                        {{-- Badges + revoke --}}
                        <div class="dv-badges">
                            @if($device->is_current)
                                <span class="dv-badge-current">This device</span>
                            @else
                                <form method="POST"
                                      action="{{ route('devices.destroy', $device->id) }}"
                                      onsubmit="return confirm('Revoke this device session?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dv-revoke">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Revoke
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Upgrade nudge if at limit --}}
        @if($isFull)
            <div style="margin-top:1.5rem;padding:1rem 1.25rem;background:#fff8e1;
                        border:1px solid #ffe082;border-radius:.75rem;font-size:.85rem;color:#7a5800;">
                <strong>Device limit reached.</strong>
                Your <strong>{{ $pkg?->name ?? 'Free' }}</strong> plan allows {{ $limit }} device{{ $limit === 1 ? '' : 's' }}.
                <a href="{{ route('pricing') }}" style="color:#4d7d47;font-weight:700;text-decoration:none">
                    Upgrade your plan →
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
