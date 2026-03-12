<nav x-data="{ open: false }" style="background:#fff;border-bottom:2px solid var(--border);position:sticky;top:0;z-index:50;box-shadow:0 2px 12px rgba(13,31,12,.07)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- ── Logo + Nav links ── -->
            <div class="flex items-center">
                <!-- Brand -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center gap-2.5 no-underline" style="text-decoration:none;margin-right:1.5rem">
                    <img src="{{ asset('images/mindful-nutrico.png') }}"
                         alt="MindfulNutrico"
                         style="height:2.4rem;width:auto;object-fit:contain;display:block">
                    <span style="font-weight:800;font-size:1.05rem;color:var(--primary-dark);letter-spacing:-.025em;line-height:1">mindful<span style="color:var(--secondary)">nutrico</span></span>
                </a>

                <!-- Desktop nav links -->
                <div class="hidden space-x-0.5 sm:flex sm:items-center">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:.6rem;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .15s;
                              {{ request()->routeIs('dashboard') ? 'background:#e8f5e6;color:var(--primary-dark)' : 'color:var(--text-muted)' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Dashboard
                    </a>

                    {{-- Patients --}}
                    <a href="{{ route('patients.index') }}"
                       style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:.6rem;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .15s;
                              {{ request()->routeIs('patients.*') ? 'background:#e8f5e6;color:var(--primary-dark)' : 'color:var(--text-muted)' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/>
                        </svg>
                        Patients
                    </a>

                    {{-- Meal Library --}}
                    <a href="{{ route('meal-items.index') }}"
                       style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:.6rem;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .15s;
                              {{ request()->routeIs('meal-items.*') ? 'background:#e8f5e6;color:var(--primary-dark)' : 'color:var(--text-muted)' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Meal Library
                    </a>

                    {{-- Planner dropdown --}}
                    <div style="position:relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                style="display:inline-flex;align-items:center;gap:.35rem;padding:.42rem .85rem;border-radius:.6rem;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .15s;background:none;border:none;cursor:pointer;
                                       {{ request()->routeIs('meal-planner.*','pantry.*','grocery-lists.*') ? 'background:#d8ede6;color:var(--secondary-dark)' : 'color:var(--text-muted)' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Planner
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.75rem;height:.75rem;transition:transform .2s" :style="open ? 'transform:rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                             style="position:absolute;top:calc(100% + .35rem);left:0;background:#fff;border:1px solid var(--border);border-radius:10px;min-width:180px;box-shadow:0 8px 28px rgba(13,31,12,.13);z-index:50;overflow:hidden">
                             <a href="{{ route('meal-planner.index') }}"  class="mn-dd-link">🗓️ Weekly Plans</a>
                            <a href="{{ route('grocery-lists.index') }}" class="mn-dd-link">🛒 Grocery Lists</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── User dropdown ── -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="display:inline-flex;align-items:center;gap:.6rem;padding:.38rem .75rem;border-radius:.75rem;border:1.5px solid var(--border);background:#fff;cursor:pointer;transition:all .15s" class="hover:border-green-400">
                            <div style="width:2rem;height:2rem;background:linear-gradient(135deg,var(--primary-dark),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:800;flex-shrink:0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div style="text-align:left">
                                <div style="font-size:.8rem;font-weight:700;color:var(--text-primary);line-height:1.1">{{ Auth::user()->name }}</div>
                                <div style="font-size:.68rem;color:var(--text-muted);letter-spacing:.03em">{{ Auth::user()->dietician_number }}</div>
                            </div>
                            <svg class="fill-current" style="width:.85rem;height:.85rem;color:var(--text-muted)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                        <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('billing')">
                            <span style="display:flex;align-items:center;gap:.45rem">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Billing
                            </span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('devices.index')">
                            <span style="display:flex;align-items:center;gap:.45rem">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8M12 17v4"/>
                                </svg>
                                Devices
                            </span>
                        </x-dropdown-link>
                        @if (auth()->user()->isSubscriptionOwner())
                        <x-dropdown-link :href="route('team.index')">
                            <span style="display:flex;align-items:center;gap:.45rem">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Team
                            </span>
                        </x-dropdown-link>
                        @endif
                            <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- ── Hamburger ── -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ── Dropdown link styles ── -->
    <style>
        .mn-dd-link {
            display:flex;align-items:center;gap:.5rem;
            padding:.6rem 1rem;font-size:.83rem;font-weight:600;
            color:var(--text-primary);text-decoration:none;
            transition:background .12s;
        }
        .mn-dd-link:hover { background:var(--bg-page); color:var(--primary-dark); }
    </style>

    <!-- ── Mobile menu ── -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="border-top:1px solid var(--border)">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('dashboard')"          :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patients.index')"     :active="request()->routeIs('patients.*')">{{ __('Patients') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('meal-items.index')"   :active="request()->routeIs('meal-items.*')">{{ __('Meal Library') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('meal-planner.index')" :active="request()->routeIs('meal-planner.*')">{{ __('Weekly Plans') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('grocery-lists.index')" :active="request()->routeIs('grocery-lists.*')">{{ __('Grocery Lists') }}</x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
            <div style="font-weight:700;font-size:.9rem;color:var(--text-primary)">{{ Auth::user()->name }}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">{{ Auth::user()->dietician_number }}</div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('devices.index')" :active="request()->routeIs('devices.*')">{{ __('Devices') }}</x-responsive-nav-link>
                    @if (auth()->user()->isSubscriptionOwner())
                    <x-responsive-nav-link :href="route('team.index')" :active="request()->routeIs('team.*')">{{ __('Team') }}</x-responsive-nav-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
