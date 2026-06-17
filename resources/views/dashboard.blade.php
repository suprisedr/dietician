<x-app-layout>

    <div class="dash2-wrap">

        {{-- ═════════ TOP BAR: Greeting + Search ═════════ --}}
        <div class="dash2-topbar">
            <div class="dash2-greeting">
                <p class="dash2-date">{{ now()->format('l, d F Y') }}</p>
                <h1>
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                    {{ explode(' ', Auth::user()->name)[0] }}
                    <span class="dash2-wave">👋</span>
                </h1>
                <p class="dash2-sub">Here's a snapshot of your practice today.</p>
            </div>

            <form method="GET" action="{{ route('patients.index') }}" class="dash2-search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input name="search" type="text" placeholder="Search patients" autocomplete="off"/>
            </form>
        </div>

        {{-- ═════════ HERO BANNER (green gradient) ═════════ --}}
        <div class="dash2-hero">
            <div class="dash2-hero-leaf dash2-hero-leaf--tl"></div>
            <div class="dash2-hero-leaf dash2-hero-leaf--br"></div>

            <div class="dash2-hero-info">
                <div class="dash2-hero-id">
                    <div class="dash2-hero-id-label">Dietitian ID</div>
                    <div class="dash2-hero-id-value">{{ Auth::user()->dietician_number }}</div>
                </div>

                <div class="dash2-hero-divider"></div>

                <div class="dash2-hero-name">
                    <div class="dash2-hero-name-value">{{ Auth::user()->name }}</div>
                    <div class="dash2-hero-name-role">Registered Dietitian</div>
                </div>

                <div class="dash2-hero-divider"></div>

                <div class="dash2-hero-count">
                    <div class="dash2-hero-count-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="dash2-hero-count-value">{{ $patientCount }}</div>
                    <div class="dash2-hero-count-label">patient{{ $patientCount !== 1 ? 's' : '' }} registered</div>
                </div>
            </div>

            {{-- Decorative food bowl illustration --}}
            <div class="dash2-hero-bowl" aria-hidden="true">
                <svg viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
                    {{-- bowl --}}
                    <ellipse cx="110" cy="160" rx="95" ry="20" fill="rgba(0,0,0,.18)"/>
                    <path d="M20 110 Q 110 230 200 110 Z" fill="#f5f1e8"/>
                    <ellipse cx="110" cy="110" rx="90" ry="22" fill="#fff" opacity=".7"/>
                    {{-- avocado slices --}}
                    <path d="M40 100 Q 60 70 80 100 Q 60 115 40 100 Z" fill="#9bbf6f"/>
                    <path d="M48 98 Q 60 80 72 98 Q 60 108 48 98 Z" fill="#cde0a4"/>
                    <path d="M50 115 Q 70 88 90 115 Q 70 130 50 115 Z" fill="#9bbf6f"/>
                    <path d="M58 113 Q 70 95 82 113 Q 70 123 58 113 Z" fill="#cde0a4"/>
                    {{-- tomatoes --}}
                    <circle cx="130" cy="95" r="11" fill="#e74c3c"/>
                    <circle cx="127" cy="92" r="3" fill="#fff" opacity=".5"/>
                    <circle cx="155" cy="105" r="9" fill="#e74c3c"/>
                    <circle cx="152" cy="102" r="2.5" fill="#fff" opacity=".5"/>
                    <circle cx="175" cy="95" r="10" fill="#e74c3c"/>
                    <circle cx="172" cy="92" r="2.5" fill="#fff" opacity=".5"/>
                    {{-- chickpeas --}}
                    <circle cx="100" cy="105" r="6" fill="#e8c179"/>
                    <circle cx="112" cy="100" r="5" fill="#e8c179"/>
                    <circle cx="120" cy="115" r="5.5" fill="#e8c179"/>
                    <circle cx="103" cy="118" r="5" fill="#e8c179"/>
                    {{-- greens --}}
                    <path d="M85 80 Q 95 65 105 80 Q 95 85 85 80 Z" fill="#5fa854"/>
                    <path d="M140 75 Q 150 60 160 75 Q 150 80 140 75 Z" fill="#5fa854"/>
                    <path d="M165 80 Q 175 65 185 80 Q 175 85 165 80 Z" fill="#5fa854"/>
                    <path d="M115 78 Q 125 63 135 78 Q 125 83 115 78 Z" fill="#7bbd6b"/>
                    {{-- quinoa specks --}}
                    <circle cx="92" cy="118" r="1.5" fill="#d4b56a"/>
                    <circle cx="98" cy="125" r="1.5" fill="#d4b56a"/>
                    <circle cx="115" cy="125" r="1.5" fill="#d4b56a"/>
                    <circle cx="130" cy="120" r="1.5" fill="#d4b56a"/>
                    <circle cx="145" cy="123" r="1.5" fill="#d4b56a"/>
                </svg>
            </div>
        </div>

        {{-- ═════════ QUICK ACTIONS ═════════ --}}
        <section class="dash2-section">
            <h2 class="dash2-section-title">Quick Actions</h2>
            <div class="dash2-quick-grid">

                <a href="{{ route('patients.create') }}" class="dash2-quick-card">
                    <span class="dash2-quick-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/>
                        </svg>
                    </span>
                    <div class="dash2-quick-text">
                        <div class="dash2-quick-title">New Patient</div>
                        <div class="dash2-quick-sub">Add a new patient</div>
                    </div>
                    <svg class="dash2-quick-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="{{ route('patients.index') }}" class="dash2-quick-card">
                    <span class="dash2-quick-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                        </svg>
                    </span>
                    <div class="dash2-quick-text">
                        <div class="dash2-quick-title">All Patients</div>
                        <div class="dash2-quick-sub">View all patients</div>
                    </div>
                    <svg class="dash2-quick-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="{{ route('profile.edit') }}" class="dash2-quick-card">
                    <span class="dash2-quick-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </span>
                    <div class="dash2-quick-text">
                        <div class="dash2-quick-title">Profile</div>
                        <div class="dash2-quick-sub">View your profile</div>
                    </div>
                    <svg class="dash2-quick-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="{{ route('reports.index') }}" class="dash2-quick-card">
                    <span class="dash2-quick-icon" style="background:#ece2f7;color:#6f42c1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                        </svg>
                    </span>
                    <div class="dash2-quick-text">
                        <div class="dash2-quick-title">Reports</div>
                        <div class="dash2-quick-sub">View reports &amp; analytics</div>
                    </div>
                    <svg class="dash2-quick-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </section>

        {{-- ═════════ FEATURES ═════════ --}}
        <section class="dash2-section">
            <h2 class="dash2-section-title">Features</h2>
            <div class="dash2-feat-grid">

                <a href="{{ route('meal-planner.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Weekly Meal Plans</span>
                </a>

                <a href="{{ route('recipes.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#fde7d4;color:#d97706">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Recipes</span>
                </a>

                <a href="{{ route('grocery-lists.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Grocery Lists</span>
                </a>

                <a href="{{ route('food-diary.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#ece2f7;color:#6f42c1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Food Diaries</span>
                </a>

                <a href="{{ route('meal-items.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#fde7d4;color:#d97706">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Meal Library</span>
                </a>

                <a href="{{ route('email-templates.index') }}" class="dash2-feat-card">
                    <span class="dash2-feat-icon" style="background:#fcdfe9;color:#be185d">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <span class="dash2-feat-label">Email Templates</span>
                </a>
            </div>
        </section>

        {{-- ═════════ TODAY'S OVERVIEW ═════════ --}}
        <section class="dash2-overview">
            <h2 class="dash2-section-title">Today's Overview</h2>
            <div class="dash2-stats-grid">

                <div class="dash2-stat">
                    <span class="dash2-stat-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <div>
                        <div class="dash2-stat-value">{{ $patientCount }}</div>
                        <div class="dash2-stat-label">Total Patients</div>
                    </div>
                </div>

                <div class="dash2-stat">
                    <span class="dash2-stat-icon" style="background:#dcefd8;color:#4d7d47">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M12 11v6m-3-3h6"/>
                        </svg>
                    </span>
                    <div>
                        <div class="dash2-stat-value">{{ $newThisWeek }}</div>
                        <div class="dash2-stat-label">New This Week</div>
                    </div>
                </div>

                <div class="dash2-stat">
                    <span class="dash2-stat-icon" style="background:#fef3c7;color:#b45309">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <div class="dash2-stat-value">{{ $followUps }}</div>
                        <div class="dash2-stat-label">Follow Ups</div>
                    </div>
                </div>

                <div class="dash2-stat">
                    <span class="dash2-stat-icon" style="background:#fcdfe9;color:#be185d">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <div>
                        <div class="dash2-stat-value">{{ $mealPlansCreated }}</div>
                        <div class="dash2-stat-label">Meal Plans Created</div>
                    </div>
                </div>
            </div>
        </section>

    </div>

</x-app-layout>
