<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);letter-spacing:-.025em;margin:0">
            Billing &amp; Subscription
        </h2>
    </x-slot>

    <style>
        .bl-wrap      { max-width: 860px; margin: 2rem auto; padding: 0 1.25rem 4rem; }

        /* Flash */
        .bl-flash     { padding: .75rem 1.1rem; border-radius: .65rem; font-size: .85rem;
                        font-weight: 600; margin-bottom: 1.5rem; }
        .bl-flash.success { background: #e8f5e6; color: #2e6e56; border: 1px solid #c3e6c0; }
        .bl-flash.error   { background: #fef2f2; color: #c0392b; border: 1px solid #fecaca; }

        /* Section heading */
        .bl-section-title { font-size: .72rem; font-weight: 800; text-transform: uppercase;
                            letter-spacing: .08em; color: var(--text-muted); margin-bottom: .85rem; }

        /* ── Current plan card ── */
        .bl-current   { background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 55%, #429677 100%);
                        border-radius: 1rem; padding: 1.75rem 2rem; color: #fff;
                        display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
                        margin-bottom: 2.25rem; position: relative; overflow: hidden; }
        .bl-current::before { content: ''; position: absolute; inset: 0;
                              background: radial-gradient(circle at 85% 20%, rgba(103,159,95,.3) 0%, transparent 55%); }
        .bl-current-info { flex: 1; min-width: 0; position: relative; z-index: 1; }
        .bl-plan-name { font-size: 1.5rem; font-weight: 800; letter-spacing: -.03em; }
        .bl-plan-sub  { font-size: .85rem; color: rgba(255,255,255,.65); margin-top: .2rem; }
        .bl-status-badge { display: inline-flex; align-items: center; gap: .35rem;
                           padding: .28rem .75rem; border-radius: 999px; font-size: .72rem;
                           font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
                           margin-top: .75rem; }
        .bl-status-badge.success { background: rgba(168,213,162,.25); color: #a8d5a2; border: 1px solid rgba(168,213,162,.4); }
        .bl-status-badge.warning { background: rgba(253,224,71,.2);  color: #fde047; border: 1px solid rgba(253,224,71,.35); }
        .bl-status-badge.danger  { background: rgba(252,165,165,.2); color: #fca5a5; border: 1px solid rgba(252,165,165,.35); }
        .bl-status-badge.muted   { background: rgba(255,255,255,.1); color: rgba(255,255,255,.6); }

        .bl-current-meta { text-align: right; position: relative; z-index: 1; flex-shrink: 0; }
        .bl-next-label   { font-size: .72rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .06em; }
        .bl-next-date    { font-size: 1.05rem; font-weight: 700; margin-top: .15rem; }
        .bl-cancel-btn   { display: inline-block; margin-top: .85rem; padding: .38rem .9rem;
                               font-size: .78rem; font-weight: 700; color: rgba(255,255,255,.7);
                               border: 1.5px solid rgba(255,255,255,.25); border-radius: .5rem;
                               background: transparent; cursor: pointer; transition: all .15s; }
        .bl-cancel-btn:hover { color: #fca5a5; border-color: #fca5a5; }

        /* ── Cancel confirmation modal ── */
        .cancel-overlay { display: none; position: fixed; inset: 0; z-index: 50;
                          background: rgba(0,0,0,.55); backdrop-filter: blur(3px);
                          align-items: center; justify-content: center; padding: 1rem; }
        .cancel-overlay.open { display: flex; }
        .cancel-modal   { background: #fff; border-radius: 1.1rem; padding: 2rem 2rem 1.75rem;
                           max-width: 500px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,.2);
                           animation: modalIn .18s ease; }
        @keyframes modalIn { from { opacity:0; transform:scale(.96) translateY(8px); }
                              to   { opacity:1; transform:scale(1)  translateY(0);     } }
        .cancel-modal-icon  { width: 3rem; height: 3rem; border-radius: 999px;
                              background: #fef2f2; display: flex; align-items: center;
                              justify-content: center; margin-bottom: 1.1rem; }
        .cancel-modal h3    { font-size: 1.05rem; font-weight: 800; color: #111827;
                              margin: 0 0 .5rem; letter-spacing: -.02em; }
        .cancel-modal p     { font-size: .875rem; color: #6b7280; line-height: 1.6; margin: 0 0 .75rem; }
        .cancel-reasons     { display: grid; grid-template-columns: 1fr 1fr; gap: .4rem; margin-bottom: .85rem; }
        .cancel-reason-opt  { display: flex; align-items: center; gap: .45rem;
                              padding: .45rem .65rem; border: 1.5px solid #e5e7eb; border-radius: .5rem;
                              font-size: .8rem; color: #374151; cursor: pointer; transition: all .15s; }
        .cancel-reason-opt:has(input:checked) { border-color: #dc2626; background: #fef2f2; color: #b91c1c; font-weight: 600; }
        .cancel-reason-opt input { accent-color: #dc2626; flex-shrink: 0; }
        .cancel-other       { width: 100%; padding: .5rem .7rem; font-size: .83rem;
                              border: 1.5px solid #e5e7eb; border-radius: .5rem; resize: vertical;
                              min-height: 60px; outline: none; transition: border-color .15s;
                              box-sizing: border-box; margin-bottom: .85rem; color: #374151; }
        .cancel-other:focus  { border-color: #dc2626; }
        .cancel-modal-btns  { display: flex; gap: .75rem; justify-content: flex-end; }
        .cancel-modal-btns .btn-keep    { padding: .55rem 1.2rem; border-radius: .6rem; font-size: .85rem;
                                          font-weight: 600; border: 1.5px solid #d1d5db; background: #fff;
                                          color: #374151; cursor: pointer; transition: all .15s; }
        .cancel-modal-btns .btn-keep:hover    { background: #f9fafb; }
        .cancel-modal-btns .btn-confirm { padding: .55rem 1.2rem; border-radius: .6rem; font-size: .85rem;
                                          font-weight: 700; background: #dc2626; border: none;
                                          color: #fff; cursor: pointer; transition: background .15s; }
        .cancel-modal-btns .btn-confirm:hover { background: #b91c1c; }
        .bl-packages  { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2.5rem; }
        @media (max-width: 700px) { .bl-packages { grid-template-columns: 1fr; } }

        .bl-pkg-card  { background: #fff; border: 1.5px solid var(--border, #d4e6d1);
                        border-radius: .9rem; padding: 1.4rem 1.25rem 1.5rem;
                        display: flex; flex-direction: column; transition: box-shadow .15s; }
        .bl-pkg-card:hover { box-shadow: 0 6px 24px rgba(13,31,12,.09); }
        .bl-pkg-card.current-plan { border-color: #679F5F; background: #f4fbf3; }

        .bl-pkg-badge { display: inline-block; font-size: .65rem; font-weight: 800;
                        text-transform: uppercase; letter-spacing: .07em; padding: .2rem .65rem;
                        border-radius: 999px; margin-bottom: .85rem; width: fit-content; }
        .bl-pkg-badge.popular { background: #679F5F; color: #fff; }
        .bl-pkg-badge.pro     { background: #e0f2ee; color: #2e6e56; }
        .bl-pkg-badge.team    { background: #d8ede6; color: #1a4a3a; }
        .bl-pkg-badge.current { background: #e8f5e6; color: #4d7d47; }

        .bl-pkg-name  { font-size: 1rem; font-weight: 800; color: var(--text-primary); }
        .bl-pkg-price { font-size: 1.65rem; font-weight: 800; color: var(--text-primary);
                        letter-spacing: -.04em; margin: .25rem 0 .2rem; }
        .bl-pkg-price span { font-size: .8rem; font-weight: 500; color: var(--text-muted); }
        .bl-pkg-tagline { font-size: .78rem; color: var(--text-muted); margin-bottom: 1rem; }

        .bl-pkg-features { list-style: none; flex: 1; margin-bottom: 1.25rem;
                           display: flex; flex-direction: column; gap: .5rem; }
        .bl-pkg-features li { display: flex; align-items: flex-start; gap: .5rem;
                              font-size: .8rem; color: var(--text-primary); }
        .bl-pkg-check { width: .95rem; height: .95rem; flex-shrink: 0; margin-top: .1rem; color: #679F5F; }

        .bl-subscribe-btn { display: block; width: 100%; text-align: center; padding: .6rem 1rem;
                            font-size: .85rem; font-weight: 700; border-radius: .55rem;
                            text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .bl-subscribe-btn.green { background: linear-gradient(135deg,#679F5F,#429677); color: #fff;
                                  box-shadow: 0 4px 14px rgba(103,159,95,.35); }
        .bl-subscribe-btn.green:hover { box-shadow: 0 8px 20px rgba(103,159,95,.5); color: #fff; }
        .bl-subscribe-btn.disabled { background: #e8f5e6; color: #4d7d47; cursor: default; pointer-events: none; }

        /* ── Subscription history ── */
        .bl-history   { background: #fff; border: 1px solid var(--border,#d4e6d1);
                        border-radius: .85rem; overflow: hidden; }
        .bl-history table { width: 100%; border-collapse: collapse; font-size: .83rem; }
        .bl-history thead th { padding: .75rem 1rem; text-align: left; font-size: .7rem;
                               font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
                               color: var(--text-muted); background: #f0f7ef;
                               border-bottom: 1px solid var(--border,#d4e6d1); }
        .bl-history tbody td { padding: .75rem 1rem; border-bottom: 1px solid #edf5ec;
                               color: var(--text-primary); }
        .bl-history tbody tr:last-child td { border-bottom: none; }
        .bl-history tbody tr:hover td { background: #f9fdf9; }

        .hs-badge { display: inline-block; padding: .15rem .55rem; border-radius: 999px;
                    font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
        .hs-badge.active       { background: #e8f5e6; color: #2e6e56; }
        .hs-badge.non_renewing { background: #fef9e7; color: #7a5800; }
        .hs-badge.cancelled    { background: #fef2f2; color: #c0392b; }
        .hs-badge.expired      { background: #fef2f2; color: #c0392b; }
        .hs-badge.pending      { background: #f0f7ef; color: var(--text-muted); }
    </style>

    <div class="bl-wrap">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bl-flash success">{{ session('success') }}</div>
        @endif
        @foreach($errors->all() as $error)
            <div class="bl-flash error">{{ $error }}</div>
        @endforeach

        {{-- ── Current plan ── --}}
        <p class="bl-section-title">Current Plan</p>
        <div class="bl-current">
            <div class="bl-current-info">
                <div class="bl-plan-name">{{ $user->pricingPackage?->name ?? 'Free' }}</div>
                <div class="bl-plan-sub">{{ $user->pricingPackage?->tagline ?? 'Essential calculators to get started' }}</div>

                @if($subscription)
                    @php $style = $subscription->statusStyle(); @endphp
                    <div class="bl-status-badge {{ $style }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.75rem;height:.75rem"
                             fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="4"/>
                        </svg>
                        {{ $subscription->statusLabel() }}
                    </div>
                @else
                    <div class="bl-status-badge muted">Free plan</div>
                @endif
            </div>

            <div class="bl-current-meta">
                @if($subscription && $subscription->isActive() && $subscription->next_payment_at)
                    <div class="bl-next-label">Next billing</div>
                    <div class="bl-next-date">{{ $subscription->next_payment_at->format('d M Y') }}</div>
                @elseif($subscription && $subscription->ends_at)
                    <div class="bl-next-label">Access until</div>
                    <div class="bl-next-date">{{ $subscription->ends_at->format('d M Y') }}</div>
                @else
                    <div class="bl-next-label">Amount</div>
                    <div class="bl-next-date">R 0 / mo</div>
                @endif

                @if($subscription && $subscription->isActive())
                    <button type="button" class="bl-cancel-btn"
                            onclick="document.getElementById('cancelModal').classList.add('open')">
                        Cancel subscription
                    </button>
                @endif
            </div>
        </div>

        {{-- ── Upgrade options ── --}}
        <p class="bl-section-title">Available Plans</p>
        <div class="bl-packages">
            @foreach($packages as $pkg)
                @php $isCurrent = $user->pricing_package_slug === $pkg->slug && $subscription?->isActive(); @endphp
                <div class="bl-pkg-card {{ $isCurrent ? 'current-plan' : '' }}">

                    @if($isCurrent)
                        <span class="bl-pkg-badge current">Current plan</span>
                    @else
                        <span class="bl-pkg-badge {{ $pkg->badge_style }}">{{ $pkg->badge_label }}</span>
                    @endif

                    <div class="bl-pkg-name">{{ $pkg->name }}</div>
                    <div class="bl-pkg-price">R {{ number_format($pkg->price_zar) }}<span> / month</span></div>
                    <div class="bl-pkg-tagline">{{ $pkg->tagline }}</div>

                    <ul class="bl-pkg-features">
                        @foreach($pkg->features as $feature)
                            <li>
                                <svg class="bl-pkg-check" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    @if($isCurrent)
                        <button class="bl-subscribe-btn disabled">✓ Current plan</button>
                    @else
                        <a href="{{ route('subscription.checkout', $pkg->slug) }}"
                           class="bl-subscribe-btn green">
                            {{ $user->hasActiveSubscription() ? 'Switch to ' . $pkg->name : 'Subscribe — R ' . number_format($pkg->price_zar) . '/mo' }}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ── Subscription history ── --}}
        <p class="bl-section-title">Subscription History</p>
        <div class="bl-history">
            @if($user->subscriptions->isEmpty())
                <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.85rem">
                    No subscription history yet.
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Next / Ends</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->subscriptions()->latest()->get() as $sub)
                            <tr>
                                <td style="font-weight:600">{{ $sub->package?->name ?? $sub->pricing_package_slug }}</td>
                                <td>R {{ number_format($sub->amount_zar) }}</td>
                                <td><span class="hs-badge {{ $sub->status }}">{{ $sub->statusLabel() }}</span></td>
                                <td>{{ $sub->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($sub->next_payment_at)
                                        {{ $sub->next_payment_at->format('d M Y') }}
                                    @elseif($sub->ends_at)
                                        {{ $sub->ends_at->format('d M Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

    {{-- ── Cancel subscription confirmation modal ── --}}
    <div class="cancel-overlay" id="cancelModal"
         onclick="if(event.target===this) this.classList.remove('open')">
        <div class="cancel-modal" role="dialog" aria-modal="true"
             aria-labelledby="cancelModalTitle">

            {{-- Icon --}}
            <div class="cancel-modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                     fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>

            <h3 id="cancelModalTitle">Cancel your subscription?</h3>
            <p>
                You'll keep full access to all features until the end of your
                current billing period
                @if($subscription?->next_payment_at)
                    (<strong>{{ $subscription->next_payment_at->format('d M Y') }}</strong>)
                @endif
                . After that your account will revert to the Free plan.
            </p>

            <div class="cancel-modal-btns">
                <button type="button" class="btn-keep"
                        onclick="document.getElementById('cancelModal').classList.remove('open')">
                    Keep subscription
                </button>

                <form method="POST" action="{{ route('subscription.cancel') }}" style="margin:0" id="cancel-form">
                    @csrf
                    {{-- Reason radio options --}}
                    <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin:.85rem 0 .5rem">Why are you cancelling? (optional)</p>
                    <div class="cancel-reasons">
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Too expensive"> Too expensive</label>
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Not using it enough"> Not using it enough</label>
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Missing features I need"> Missing features</label>
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Switching to another tool"> Switching tools</label>
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Technical issues"> Technical issues</label>
                        <label class="cancel-reason-opt"><input type="radio" name="cancel_reason" value="Other"> Other</label>
                    </div>
                    <textarea name="cancel_feedback" class="cancel-other" placeholder="Any additional feedback? (optional)"></textarea>
                    <div style="display:flex;justify-content:flex-end;gap:.75rem">
                        <button type="button" class="btn-keep"
                                onclick="document.getElementById('cancelModal').classList.remove('open')">
                            Keep subscription
                        </button>
                        <button type="submit" class="btn-confirm">
                            Yes, cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
