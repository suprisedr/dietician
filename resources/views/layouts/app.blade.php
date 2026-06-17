<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MindfulNutrico') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/mindful-nutrico.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- optional overrides for Soft UI / Bootstrap palette -->
        <link href="{{ asset('css/soft-ui-overrides.css') }}" rel="stylesheet">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#679F5F">
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/service-worker.js');
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased" style="background:var(--bg-page,#f8fafc)">
        <div class="app-shell {{ ($minimal ?? false) ? 'is-minimal' : '' }}">
            @unless($minimal ?? false)
                @include('layouts.navigation')
            @endunless

            <div class="app-main">
                @auth
                @unless($minimal ?? false)
                @php
                    $unreadCount         = Auth::user()->unreadNotifications()->count();
                    $recentNotifications = Auth::user()->notifications()->latest()->take(10)->get();
                @endphp
                <header class="app-topbar-desktop">
                    {{-- ── Notification bell ── --}}
                    <div class="app-notif-wrap" id="app-notif-wrap">
                        <button class="app-bell" type="button" aria-label="Notifications"
                                onclick="appNotifToggle()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                            <span class="app-bell-dot">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>

                        <div class="app-notif-panel" id="app-notif-panel" style="display:none" role="dialog" aria-label="Notifications">
                            <div class="app-notif-head">
                                <span class="app-notif-head-title">Notifications</span>
                                @if($unreadCount > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="app-notif-readall">Mark all read</button>
                                </form>
                                @endif
                            </div>

                            <div class="app-notif-list">
                                @forelse($recentNotifications as $notif)
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                    @csrf
                                    <button type="submit" class="app-notif-item {{ $notif->read_at ? '' : 'is-unread' }}">
                                        <span class="app-notif-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </span>
                                        <span class="app-notif-body">
                                            <span class="app-notif-title">
                                                {{ $notif->data['patient_name'] }} submitted a food diary
                                            </span>
                                            <span class="app-notif-meta">
                                                {{ $notif->data['diary_date'] }}
                                                @if(!empty($notif->data['rating']))
                                                · {{ $notif->data['rating'] }}/5 ★
                                                @endif
                                            </span>
                                            <span class="app-notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                        </span>
                                        @if(!$notif->read_at)
                                        <span class="app-notif-unread-dot"></span>
                                        @endif
                                    </button>
                                </form>
                                @empty
                                <div class="app-notif-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span>No notifications yet</span>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>{{-- /.app-notif-wrap --}}

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="app-userpill" type="button">
                                <div class="app-userpill-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="app-userpill-text">
                                    <div class="app-userpill-name">{{ Auth::user()->name }}</div>
                                    <div class="app-userpill-id">{{ Auth::user()->dietician_number }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="app-userpill-chev">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('billing')">Billing</x-dropdown-link>
                            <x-dropdown-link :href="route('devices.index')">Devices</x-dropdown-link>
                            @if (auth()->user()->isSubscriptionOwner())
                            <x-dropdown-link :href="route('team.index')">Team</x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </header>
                @endunless
                @endauth

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- Global upgrade modal — triggered by RequiresPlan middleware on save attempts --}}
        <x-upgrade-modal />

        {{-- ── Session inactivity logout ───────────────────────────────── --}}
        @auth
        {{-- Hidden logout form (POST required by Laravel) --}}
        <form id="session-logout-form" method="POST" action="{{ route('logout') }}" style="display:none">
            @csrf
        </form>

        {{-- Warning modal --}}
        <div id="session-warning"
             style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);align-items:center;justify-content:center">
            <div style="background:#fff;border-radius:1.25rem;padding:2rem 2.25rem;max-width:380px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,.2);text-align:center">
                <div style="width:3.25rem;height:3.25rem;background:#fff7ed;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .9rem">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.6rem;height:1.6rem;color:#f97316" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:.45rem">Session expiring soon</h3>
                <p style="font-size:.85rem;color:#64748b;line-height:1.55;margin-bottom:1.25rem">
                    Due to inactivity your session will expire in
                    <strong id="session-countdown" style="color:#f97316">60</strong> seconds.
                    Any unsaved work will be lost.
                </p>
                <div style="display:flex;gap:.75rem;justify-content:center">
                    <button onclick="sessionKeepAlive()"
                            style="flex:1;padding:.7rem 1rem;background:linear-gradient(135deg,#679F5F,#429677);color:#fff;font-size:.88rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer">
                        Stay logged in
                    </button>
                    <button onclick="sessionLogout()"
                            style="flex:1;padding:.7rem 1rem;background:#f1f5f9;color:#64748b;font-size:.88rem;font-weight:600;border:1.5px solid #e2e8f0;border-radius:.75rem;cursor:pointer">
                        Log out now
                    </button>
                </div>
            </div>
        </div>

        <script>
        (function () {
            const TIMEOUT     = 10 * 60 * 1000;   // 10 min
            const WARN_BEFORE = 60 * 1000;         // warn 60 s before
            const warning     = document.getElementById('session-warning');
            const countdown   = document.getElementById('session-countdown');

            let logoutTimer, warnTimer, countdownInterval;

            function reset() {
                clearTimeout(logoutTimer);
                clearTimeout(warnTimer);
                clearInterval(countdownInterval);

                if (warning) warning.style.display = 'none';

                warnTimer = setTimeout(showWarning, TIMEOUT - WARN_BEFORE);
                logoutTimer = setTimeout(sessionLogout, TIMEOUT);
            }

            function showWarning() {
                if (!warning) return;
                warning.style.display = 'flex';
                let secs = Math.round(WARN_BEFORE / 1000);
                if (countdown) countdown.textContent = secs;
                countdownInterval = setInterval(function () {
                    secs--;
                    if (countdown) countdown.textContent = secs;
                    if (secs <= 0) clearInterval(countdownInterval);
                }, 1000);
            }

            // Activity events that reset the timer
            ['mousemove','mousedown','keydown','scroll','touchstart','click'].forEach(function (evt) {
                document.addEventListener(evt, reset, { passive: true });
            });

            reset(); // start on page load
        })();

        function sessionKeepAlive() {
            // Ping the server to refresh the session, then hide the modal
            fetch(window.location.href, { method: 'HEAD', credentials: 'same-origin' })
                .catch(function () {});
            document.getElementById('session-warning').style.display = 'none';
            // Trigger a full timer reset by simulating activity
            document.dispatchEvent(new Event('mousemove'));
        }

        function sessionLogout() {
            document.getElementById('session-logout-form').submit();
        }
        </script>
        @endauth

        {{-- ── Global PDF Preview Modal ─────────────────────────────────── --}}
        <div id="global-pdf-overlay" role="dialog" aria-modal="true" aria-label="PDF Preview"
             style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.55);align-items:center;justify-content:center">
            <div style="background:#fff;border-radius:1rem;width:92vw;max-width:960px;height:90vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.3)">
                <div style="padding:.9rem 1.25rem;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;flex-shrink:0">
                    <span style="display:flex;align-items:center;gap:.5rem;font-size:.9rem;font-weight:700;color:#0f172a">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:#15803d" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        PDF Preview
                    </span>
                    <div style="display:flex;align-items:center;gap:.5rem">
                        <a id="global-pdf-dl-btn" href="#" target="_blank"
                           style="display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1rem;background:#15803d;color:#fff;font-size:.8rem;font-weight:700;border-radius:.5rem;text-decoration:none">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.8rem;height:.8rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download PDF
                        </a>
                        <button type="button" onclick="closeGlobalPdfPreview()"
                                style="width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#f8fafc;font-size:1.1rem;cursor:pointer;color:#64748b">&times;</button>
                    </div>
                </div>
                <div id="global-pdf-loading" style="display:none;flex:1;align-items:center;justify-content:center;gap:.5rem;color:#64748b;font-size:.875rem">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;animation:gpdf-spin 1s linear infinite" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-6.219-8.56"/></svg>
                    Loading preview&hellip;
                </div>
                <iframe id="global-pdf-frame" title="PDF Preview" style="flex:1;width:100%;border:none"></iframe>
            </div>
        </div>
        <style>
        #global-pdf-overlay.open { display:flex !important; }
        @keyframes gpdf-spin { to { transform:rotate(360deg); } }
        </style>
        <script>
        (function () {
            var overlay = document.getElementById('global-pdf-overlay');
            var frame   = document.getElementById('global-pdf-frame');
            var loading = document.getElementById('global-pdf-loading');
            var dlBtn   = document.getElementById('global-pdf-dl-btn');

            window.openPdfPreviewModal = function (streamUrl, downloadUrl) {
                dlBtn.href = downloadUrl;
                loading.style.display = 'flex';
                frame.src = '';
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
                frame.onload = function () { loading.style.display = 'none'; };
                frame.src = streamUrl;
            };

            window.closeGlobalPdfPreview = function () {
                overlay.classList.remove('open');
                document.body.style.overflow = '';
                frame.src = '';
            };

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeGlobalPdfPreview();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay.classList.contains('open')) closeGlobalPdfPreview();
            });
        }());
        </script>

        {{-- ── Notification panel toggle ────────────────────────────────── --}}
        <script>
        (function () {
            var panel = document.getElementById('app-notif-panel');

            window.appNotifToggle = function () {
                if (!panel) return;
                var open = panel.style.display !== 'none';
                panel.style.display = open ? 'none' : 'block';
            };

            document.addEventListener('click', function (e) {
                if (!panel || panel.style.display === 'none') return;
                var wrap = document.getElementById('app-notif-wrap');
                if (wrap && !wrap.contains(e.target)) {
                    panel.style.display = 'none';
                }
            });
        }());
        </script>
    </body>
</html>
