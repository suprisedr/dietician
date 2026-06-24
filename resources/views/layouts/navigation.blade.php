<div x-data="{
    open: false,
    collapsed: JSON.parse(localStorage.getItem('sidebar_collapsed') || 'false'),
    toggle() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('sidebar_collapsed', JSON.stringify(this.collapsed));
        document.body.classList.toggle('sidebar-collapsed', this.collapsed);
    }
}">

    {{-- ── Mobile top bar (hamburger + logo + user) ───────────── --}}
    <div class="app-mobilebar">
        <button @click="open = true" class="app-mobile-burger" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="app-mobile-logo">
            <img src="{{ asset('images/mindful-nutrico.png') }}" alt="MindfulNutrico"/>
            <span>mindful<em>nutrico</em></span>
        </a>
        <div style="width:2.5rem"></div>
    </div>

    {{-- ── Overlay backdrop (mobile) ───────────────────────────── --}}
    <div class="app-sidebar-backdrop"
         :class="{ 'is-open': open }"
         @click="open = false"></div>

    {{-- ── Sidebar ─────────────────────────────────────────────── --}}
    <aside class="app-sidebar" :class="{ 'is-open': open, 'is-collapsed': collapsed }">

        <div class="app-sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/mindful-nutrico.png') }}" alt="MindfulNutrico"/>
                <div class="app-sidebar-brand-text">
                    <div class="app-sidebar-brand-name">mindful<em>nutrico</em></div>
                    <div class="app-sidebar-brand-tag">NUTRITION &amp; WELLNESS</div>
                </div>
            </a>
        </div>

        {{-- Collapse toggle --}}
        <button @click="toggle()" class="app-sidebar-collapse-btn" :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 :style="collapsed ? 'transform:rotate(180deg)' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>

        <nav class="app-sidebar-nav">

            <a href="{{ route('dashboard') }}" title="Dashboard"
               class="app-sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('patients.index') }}" title="Patients"
               class="app-sidebar-link {{ request()->routeIs('patients.index') || request()->routeIs('patients.show') || request()->routeIs('patients.edit') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Patients</span>
            </a>

            <a href="{{ route('patients.create') }}" title="New Patient"
               class="app-sidebar-link {{ request()->routeIs('patients.create') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>New Patient</span>
            </a>

            <a href="{{ route('meal-planner.index') }}" title="Meal Plans"
               class="app-sidebar-link {{ request()->routeIs('meal-planner.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Meal Plans</span>
            </a>

            <a href="{{ route('recipes.index') }}" title="Recipes"
               class="app-sidebar-link {{ request()->routeIs('recipes.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Recipes</span>
            </a>

            <a href="{{ route('meal-items.index') }}" title="Meal Library"
               class="app-sidebar-link {{ request()->routeIs('meal-items.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Meal Library</span>
            </a>

            <a href="{{ route('grocery-lists.index') }}" title="Grocery Lists"
               class="app-sidebar-link {{ request()->routeIs('grocery-lists.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Grocery Lists</span>
            </a>

            <a href="{{ route('food-diary.index') }}" title="Food Diaries"
               class="app-sidebar-link {{ request()->routeIs('food-diary.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Food Diaries</span>
            </a>

            <a href="{{ route('email-templates.index') }}" title="Email Templates"
               class="app-sidebar-link {{ request()->routeIs('email-templates.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Email Templates</span>
            </a>

            <a href="{{ route('reports.index') }}" title="Reports"
               class="app-sidebar-link {{ request()->routeIs('reports.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Reports</span>
            </a>

            <a href="{{ route('profile.edit') }}" title="Profile"
               class="app-sidebar-link {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Profile</span>
            </a>

            <a href="{{ route('devices.index') }}" title="Settings"
               class="app-sidebar-link {{ request()->routeIs('devices.*') || request()->routeIs('billing') || request()->routeIs('team.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
                <span>Settings</span>
            </a>
        </nav>

        <div class="app-sidebar-foot">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-sidebar-link app-sidebar-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
</div>
