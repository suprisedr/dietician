{{--
  <x-plan-gate min="package_2">
      ... your feature content here ...
  </x-plan-gate>

  When the logged-in user's plan tier is below the required tier this component
  renders the slot content blurred out with a lock overlay and an upgrade CTA.
  When the user has sufficient access the content is displayed normally.

  Props:
    min  (string)  – minimum package slug required: 'package_1', 'package_2', 'package_3'
--}}
@props(['min' => 'package_1'])

@php
    $hasAccess = auth()->check() && auth()->user()->canAccessPlan($min);
    $package   = \App\Models\PricingPackage::where('slug', $min)->first();
    $planName  = $package?->name ?? ucfirst(str_replace('_', ' ', $min));
    $price     = $package ? 'R' . number_format($package->price_zar) . '/mo' : '';
@endphp

@if ($hasAccess)
    {{ $slot }}
@else
    {{-- Locked wrapper --}}
    <div class="relative rounded-xl overflow-hidden">
        {{-- Blurred content (still visible, just not usable) --}}
        <div class="pointer-events-none select-none blur-[3px] opacity-60 saturate-50">
            {{ $slot }}
        </div>

        {{-- Lock overlay --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center
                    bg-gradient-to-br from-[#0d1f0c]/60 to-[#429677]/50
                    backdrop-blur-sm rounded-xl z-10">
            <div class="bg-white/95 shadow-2xl rounded-2xl px-8 py-7 max-w-sm w-full mx-4 text-center">
                {{-- Lock icon --}}
                <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-[#429677]/10
                            flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-7 h-7 text-[#429677]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    {{ $planName }} Feature
                </h3>
                <p class="text-sm text-gray-500 mb-5">
                    This feature is included in the
                    <span class="font-medium text-[#429677]">{{ $planName }}</span>
                    plan{{ $price ? ' at ' . $price : '' }}.
                    Upgrade to unlock it.
                </p>

                <a href="{{ route('subscription.checkout', $min) }}{{ url()->previous() ? '?return_to='.urlencode(url()->previous()) : '' }}"
                   class="inline-flex items-center gap-2 bg-[#429677] hover:bg-[#679F5F]
                          text-white text-sm font-semibold px-5 py-2.5 rounded-lg
                          transition-colors duration-150 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    Upgrade to {{ $planName }}
                </a>

                <div class="mt-3">
                    <a href="{{ route('billing') }}"
                       class="text-xs text-gray-400 hover:text-[#429677] transition-colors">
                        View all plans
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
