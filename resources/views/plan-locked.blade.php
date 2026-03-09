<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Feature Locked
        </h2>
    </x-slot>

    <div class="py-16">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">

            {{-- Icon --}}
            <div class="mx-auto mb-6 w-20 h-20 rounded-full
                        bg-gradient-to-br from-[#429677]/20 to-[#679F5F]/10
                        flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-10 h-10 text-[#429677]" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            {{-- Heading --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Upgrade Required
            </h1>

            @if ($requiredPackage)
                <p class="text-gray-500 mb-8 leading-relaxed">
                    The feature you're trying to use is part of the
                    <span class="font-semibold text-[#429677]">{{ $requiredPackage->name }}</span>
                    plan
                    @if ($requiredPackage->price_zar > 0)
                        (R{{ number_format($requiredPackage->price_zar) }}/month)
                    @endif
                    .
                    Your current plan does not include this feature.
                    Upgrade to unlock it and many more.
                </p>

                {{-- Feature list for the required package --}}
                @if (! empty($requiredPackage->features))
                    <div class="bg-[#f0f7f4] rounded-xl p-5 mb-8 text-left">
                        <p class="text-xs font-semibold text-[#429677] uppercase tracking-wider mb-3">
                            What you'll get with {{ $requiredPackage->name }}
                        </p>
                        <ul class="space-y-2">
                            @foreach ($requiredPackage->features as $feature)
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-4 h-4 text-[#429677] shrink-0"
                                         viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a href="{{ route('subscription.checkout', $requiredPackage->slug) }}"
                   class="inline-flex items-center gap-2
                          bg-[#429677] hover:bg-[#679F5F]
                          text-white font-semibold px-8 py-3 rounded-xl
                          transition-colors duration-150 shadow-md shadow-[#42967730]
                          text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    Upgrade to {{ $requiredPackage->name }}
                </a>

            @else
                <p class="text-gray-500 mb-8">
                    You need a higher subscription plan to access this feature.
                </p>
                <a href="{{ route('billing') }}"
                   class="inline-flex items-center gap-2
                          bg-[#429677] hover:bg-[#679F5F]
                          text-white font-semibold px-8 py-3 rounded-xl
                          transition-colors duration-150 shadow-md
                          text-sm">
                    View Plans &amp; Pricing
                </a>
            @endif

            <div class="mt-5">
                <a href="{{ route('billing') }}"
                   class="text-sm text-gray-400 hover:text-[#429677] transition-colors">
                    View all plans
                </a>
                <span class="mx-2 text-gray-300">&middot;</span>
                <a href="{{ route('dashboard') }}"
                   class="text-sm text-gray-400 hover:text-[#429677] transition-colors">
                    Back to dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
