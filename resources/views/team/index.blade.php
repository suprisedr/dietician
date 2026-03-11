<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);letter-spacing:-.025em;margin:0">
            Team Management
        </h2>
    </x-slot>

    <style>
        .tm-wrap      { max-width: 820px; margin: 2rem auto; padding: 0 1.25rem 4rem; }

        /* ── Section card ── */
        .tm-card      { background: #fff; border: 1px solid var(--border,#d4e6d1);
                        border-radius: .85rem; padding: 1.5rem; margin-bottom: 1.75rem; }
        .tm-card-title{ font-size: 1rem; font-weight: 800; color: var(--text-primary);
                        margin-bottom: .2rem; }
        .tm-card-sub  { font-size: .82rem; color: var(--text-muted); margin-bottom: 1.25rem; }

        /* ── Slot bar ── */
        .tm-slots     { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
                        margin-bottom: 1.4rem; }
        .tm-slots-bar { flex: 2; min-width: 160px; background: #e8f5e6;
                        border-radius: 999px; height: .55rem; overflow: hidden; }
        .tm-slots-fill{ height: 100%; border-radius: 999px;
                        background: linear-gradient(90deg, #679F5F, #429677);
                        transition: width .4s; }
        .tm-slots-fill.full { background: linear-gradient(90deg, #e05252, #c0392b); }
        .tm-slots-lbl { font-size: .8rem; font-weight: 700; color: var(--text-muted); flex: 1; min-width: 120px; }
        .tm-slots-ct  { font-size: .85rem; font-weight: 800; color: var(--text-primary); white-space: nowrap; }

        /* ── Invite form ── */
        .tm-form      { display: flex; gap: .75rem; flex-wrap: wrap; }
        .tm-form input{ flex: 1; min-width: 200px; padding: .55rem .9rem;
                        border: 1.5px solid var(--border,#d4e6d1); border-radius: .5rem;
                        font-size: .9rem; color: var(--text-primary); outline: none; }
        .tm-form input:focus { border-color: #679F5F; box-shadow: 0 0 0 3px rgba(103,159,95,.15); }
        .tm-btn       { padding: .55rem 1.2rem; border-radius: .5rem; font-size: .875rem;
                        font-weight: 700; cursor: pointer; border: none; }
        .tm-btn-green { background: linear-gradient(135deg,#679F5F,#429677); color: #fff; }
        .tm-btn-green:hover { opacity: .9; }
        .tm-btn-danger{ background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; }
        .tm-btn-danger:hover { background: #fee2e2; }

        /* ── Member / invite rows ── */
        .tm-list      { display: flex; flex-direction: column; gap: .7rem; }
        .tm-row       { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
                        background: #fafdf9; border: 1px solid var(--border,#d4e6d1);
                        border-radius: .65rem; padding: .85rem 1rem; }
        .tm-avatar    { width: 2.2rem; height: 2.2rem; border-radius: 50%;
                        background: linear-gradient(135deg,#679F5F,#429677);
                        display: flex; align-items: center; justify-content: center;
                        font-size: .8rem; font-weight: 800; color: #fff; flex-shrink: 0; }
        .tm-info      { flex: 1; min-width: 0; }
        .tm-name      { font-size: .9rem; font-weight: 700; color: var(--text-primary);
                        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tm-email     { font-size: .78rem; color: var(--text-muted); white-space: nowrap;
                        overflow: hidden; text-overflow: ellipsis; }
        .tm-badge     { font-size: .72rem; font-weight: 700; padding: .2rem .55rem;
                        border-radius: 999px; white-space: nowrap; }
        .tm-badge-pending  { background: #fef9c3; color: #92400e; }
        .tm-badge-accepted { background: #d1fae5; color: #065f46; }

        /* ── Empty state ── */
        .tm-empty     { text-align: center; padding: 2rem 1rem; color: var(--text-muted);
                        font-size: .85rem; }
    </style>

    <div class="tm-wrap">

        {{-- Flash messages --}}
        @if (session('success'))
            <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:.65rem;
                        padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.875rem;color:#065f46">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:.65rem;
                        padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.875rem;color:#dc2626">
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Slots overview ── --}}
        @php
            $pkg        = $owner->pricingPackage;
            $maxMembers = max(0, ($pkg?->max_users ?? 1) - 1);   // slots for members (owner takes 1)
            $usedSlots  = $members->count();
            $pct        = $maxMembers > 0 ? min(100, round($usedSlots / $maxMembers * 100)) : 100;
        @endphp

        <div class="tm-card">
            <div class="tm-card-title">Team Slots</div>
            <div class="tm-card-sub">
                Your <strong>{{ $pkg?->name ?? 'current' }}</strong> plan supports
                {{ $maxMembers }} team member{{ $maxMembers === 1 ? '' : 's' }} in addition to yourself.
            </div>

            <div class="tm-slots">
                <div class="tm-slots-lbl">{{ $usedSlots }} / {{ $maxMembers }} members</div>
                <div class="tm-slots-bar">
                    <div class="tm-slots-fill {{ $pct >= 100 ? 'full' : '' }}"
                         style="width: {{ $pct }}%"></div>
                </div>
                <div class="tm-slots-ct">{{ $maxMembers - $usedSlots }} slot{{ ($maxMembers - $usedSlots) === 1 ? '' : 's' }} free</div>
            </div>

            @if ($maxMembers <= 0)
                <p style="font-size:.82rem;color:#92400e;background:#fef9c3;
                           padding:.6rem .85rem;border-radius:.5rem;margin:0">
                    Your current plan does not include team members.
                    <a href="{{ route('pricing') }}" style="color:#679F5F;font-weight:700">Upgrade your plan</a>
                    to invite team members.
                </p>
            @endif
        </div>

        {{-- ── Send invitation ── --}}
        @if ($maxMembers > 0 && $owner->remainingInviteSlots() > 0)
        <div class="tm-card">
            <div class="tm-card-title">Invite a Team Member</div>
            <div class="tm-card-sub">They will receive an email with a link to create their account.</div>

            <form method="POST" action="{{ route('team.invite') }}" class="tm-form">
                @csrf
                <input type="email" name="email" placeholder="colleague@example.com"
                       value="{{ old('email') }}" required>
                <button type="submit" class="tm-btn tm-btn-green">Send Invite</button>
            </form>
            @error('email')
                <p style="margin-top:.5rem;font-size:.8rem;color:#dc2626">{{ $message }}</p>
            @enderror
        </div>
        @endif

        {{-- ── Active members ── --}}
        <div class="tm-card">
            <div class="tm-card-title">Team Members</div>
            <div class="tm-card-sub">Users currently active on your team.</div>

            @if ($members->isEmpty())
                <div class="tm-empty">No members yet. Send an invitation above to get started.</div>
            @else
                <div class="tm-list">
                    @foreach ($members as $member)
                        <div class="tm-row">
                            <div class="tm-avatar">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                            <div class="tm-info">
                                <div class="tm-name">{{ $member->name }}</div>
                                <div class="tm-email">{{ $member->email }}</div>
                            </div>
                            <span class="tm-badge tm-badge-accepted">Active</span>
                            <form method="POST" action="{{ route('team.members.destroy', $member) }}"
                                  onsubmit="return confirm('Remove {{ $member->name }} from your team? They will lose access to your plan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tm-btn tm-btn-danger">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Pending invitations ── --}}
        @php $pending = $invitations->filter(fn($i) => $i->isPending()); @endphp
        @if ($pending->isNotEmpty())
        <div class="tm-card">
            <div class="tm-card-title">Pending Invitations</div>
            <div class="tm-card-sub">These invites have been sent but not yet accepted.</div>

            <div class="tm-list">
                @foreach ($pending as $inv)
                    <div class="tm-row">
                        <div class="tm-avatar">✉</div>
                        <div class="tm-info">
                            <div class="tm-name">{{ $inv->email }}</div>
                            <div class="tm-email">Sent {{ $inv->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="tm-badge tm-badge-pending">Pending</span>
                        <form method="POST" action="{{ route('team.invitations.destroy', $inv) }}"
                              onsubmit="return confirm('Revoke invitation to {{ $inv->email }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tm-btn tm-btn-danger">Revoke</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
