<x-app-layout>

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                {{-- Greeting --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        {{ now()->format('l, d F Y') }}
                    </p>
                    <h1>
                        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                        {{ explode(' ', Auth::user()->name)[0] }} 👋
                    </h1>
                    <p>Here's a snapshot of your practice today.</p>
                </div>

                {{-- Search bar --}}
                <form method="GET" action="{{ route('patients.index') }}" class="dash-search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input name="search" type="text" placeholder="Search patients…" autocomplete="off"/>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── QUICK ACTIONS ──────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Quick Actions</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4">
                        <a href="{{ route('patients.create') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#fff7ed,#fed7aa)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#f97316" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/>
                                </svg>
                            </div>
                            New Patient
                        </a>
                        <a href="{{ route('patients.index') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#eff6ff,#bfdbfe)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                                </svg>
                            </div>
                            All Patients
                        </a>
                        <a href="{{ route('profile.edit') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#f0fdf4,#bbf7d0)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#22c55e" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                            </div>
                            Profile
                        </a>
                        <a href="{{ route('reports.index') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#fdf4ff,#e9d5ff)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                </svg>
                            </div>
                            Reports
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── DIETICIAN INFO CARD ────────────────────────── --}}
            <div>
                {{-- ── FEATURE LINKS ──────────────────────────────── --}}
                <div class="dash-section mb-6">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Features</span>
                    </div>
                    <div class="p-3 space-y-1">
                        <a href="{{ route('meal-planner.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#eff6ff,#bfdbfe);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            Weekly Meal Plans
                        </a>
                        <a href="{{ route('recipes.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#fff7ed,#fed7aa);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#f97316" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </span>
                            Recipes
                        </a>
                        <a href="{{ route('grocery-lists.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#f0fdf4,#bbf7d0);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#22c55e" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </span>
                            Grocery Lists
                        </a>
                        <a href="{{ route('food-diary.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#fdf4ff,#e9d5ff);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </span>
                            Food Diaries
                        </a>
                        <a href="{{ route('meal-items.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#fef9c3,#fef08a);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#ca8a04" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            Meal Library
                        </a>
                        <a href="{{ route('email-templates.index') }}" style="display:flex;align-items:center;gap:.75rem;padding:.65rem .75rem;border-radius:.6rem;text-decoration:none;color:var(--text-primary);font-size:.85rem;font-weight:600;transition:background .12s" class="hover:bg-gray-50">
                            <span style="width:2rem;height:2rem;background:linear-gradient(135deg,#fce7f3,#fbcfe8);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" style="color:#ec4899" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            Email Templates
                        </a>
                    </div>
                </div>

                {{-- ── DIETICIAN INFO CARD ────────────────────────── --}}
                <div class="dash-section" style="overflow:visible">
                    <div style="background:linear-gradient(135deg,#1e1b4b,#f97316);border-radius:1.5rem;padding:1.5rem;color:#fff;position:relative;overflow:hidden">
                        <div style="position:absolute;top:-1rem;right:-1rem;width:6rem;height:6rem;background:rgba(255,255,255,.08);border-radius:50%"></div>
                        <div style="position:absolute;bottom:-2rem;left:-1rem;width:8rem;height:8rem;background:rgba(255,255,255,.06);border-radius:50%"></div>
                        <div style="position:relative;z-index:1">
                            <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;opacity:.65">Dietitian ID</div>
                            <div style="font-size:1.2rem;font-weight:800;letter-spacing:.05em;margin-top:.2rem">{{ Auth::user()->dietician_number }}</div>
                            <div style="margin-top:.75rem;font-size:.95rem;font-weight:700">{{ Auth::user()->name }}</div>
                            <div style="margin-top:.25rem;opacity:.65;font-size:.8rem">{{ $patientCount }} patient{{ $patientCount !== 1 ? 's' : '' }} registered</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
