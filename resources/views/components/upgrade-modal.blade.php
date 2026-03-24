@php
    $flashSlug    = session('upgrade_required');
    $flashPackage = $flashSlug
        ? \App\Models\PricingPackage::where('slug', $flashSlug)->first()
        : null;
@endphp

<div
    x-data="{
        show: @js((bool) $flashSlug),
        slug: @js($flashSlug ?? ''),
        planName: @js($flashPackage?->name ?? ''),
        price: @js($flashPackage ? 'R' . number_format($flashPackage->price_zar) . '/month' : ''),
        isFree: @js($flashPackage ? $flashPackage->price_zar === 0 : false),
        features: @js($flashPackage?->features ?? []),
        checkoutUrl: @js($flashPackage ? route('subscription.checkout', $flashSlug) : ''),
        billingUrl: @js(route('billing')),

        open(slug, planName, price, isFree, features, checkoutUrl) {
            this.slug        = slug;
            this.planName    = planName;
            this.price       = price;
            this.isFree      = isFree;
            this.features    = features;
            this.checkoutUrl = checkoutUrl;
            this.show        = true;
        },
        close() {
            this.show = false;
        }
    }"
    x-on:open-upgrade-modal.window="
        let pkg = $event.detail;
        open(pkg.slug ?? '', pkg.planName ?? '', pkg.price ?? '', pkg.isFree ?? false, pkg.features ?? [], pkg.checkoutUrl ?? '');
    "
    x-on:keydown.escape.window="close()"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="display: none;"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-on:click="close()"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
    ></div>

    {{-- Panel --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center z-10"
    >
        {{-- Close --}}
        <button
            type="button"
            x-on:click="close()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        {{-- Lock icon --}}
        <div class="mx-auto mb-5 w-16 h-16 rounded-full
                    bg-gradient-to-br from-[#429677]/20 to-[#679F5F]/10
                    flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-8 h-8 text-[#429677]" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        {{-- Heading --}}
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Upgrade Required</h2>

        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            This feature is included in the
            <span class="font-semibold text-[#429677]" x-text="planName"></span>
            plan
            <template x-if="!isFree && price">
                <span>(<span x-text="price"></span>)</span>
            </template>.
            Upgrade to save this information and unlock all premium features.
        </p>

        {{-- Feature list --}}
        <template x-if="features.length > 0">
            <div class="bg-[#f0f7f4] rounded-xl p-4 mb-6 text-left">
                <p class="text-xs font-semibold text-[#429677] uppercase tracking-wider mb-3">
                    What you'll get
                </p>
                <ul class="space-y-2">
                    <template x-for="feature in features" :key="feature">
                        <li class="flex items-center gap-2 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4 text-[#429677] shrink-0"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span x-text="feature"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </template>

        {{-- CTA --}}
        <a
            :href="checkoutUrl"
            class="inline-flex items-center justify-center gap-2 w-full
                   bg-[#429677] hover:bg-[#679F5F] text-white
                   text-sm font-semibold px-5 py-3 rounded-xl
                   transition-colors duration-150 shadow-sm mb-3"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
            <span>Upgrade to <span x-text="planName"></span></span>
        </a>

        <a
            :href="billingUrl"
            class="text-xs text-gray-400 hover:text-[#429677] transition-colors"
        >
            View all plans
        </a>
    </div>
</div>
