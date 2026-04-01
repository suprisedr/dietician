<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pending Verification
            </h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Pending DT Number Confirmation
            </span>
        </div>
    </x-slot>

    <div class="py-16">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">

            {{-- Lock icon --}}
            <div class="mx-auto mb-6 w-20 h-20 rounded-full
                        bg-gradient-to-br from-amber-100 to-orange-50
                        flex items-center justify-center border-2 border-amber-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-amber-600"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            {{-- Heading --}}
            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                Pending Verification
            </h1>
            <p class="text-amber-700 font-semibold text-sm mb-5">
                Pending Dietitian (DT) Number Confirmation
            </p>

            <p class="text-gray-600 text-base leading-relaxed mb-6">
                Access to Mindfulnutrico is locked until your Dietitian number
                <strong class="text-gray-800">{{ auth()->user()->dietician_number }}</strong>
                has been verified against the official HPCSA iRegister by our admin team.
            </p>

            {{-- DT number callout --}}
            <div class="bg-white border-2 border-amber-300 rounded-xl p-4 mb-6 text-center">
                <p class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-1">Your DT / HPCSA Number</p>
                <p class="text-2xl font-extrabold text-gray-900 tracking-wider">{{ auth()->user()->dietician_number }}</p>
                <p class="text-xs text-gray-400 mt-1">This is what our admin is verifying</p>
            </div>

            {{-- What happens next --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-left mb-6">
                <p class="text-sm font-semibold text-amber-800 mb-4 uppercase tracking-wide">What happens next?</p>
                <ol class="space-y-4 text-sm text-amber-900">

                    {{-- Step 1 --}}
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 w-6 h-6 rounded-full bg-amber-300 text-amber-900 text-xs font-bold flex items-center justify-center flex-shrink-0 shadow-sm">1</span>
                        <div>
                            <p class="font-semibold text-amber-900 mb-1">HPCSA iRegister lookup</p>
                            <p class="leading-relaxed">
                                Our admin searches for DT number
                                <span class="inline-flex items-center gap-1 mx-0.5 px-2 py-0.5 rounded-md bg-white border border-amber-300 font-mono font-bold text-amber-900 text-xs tracking-widest shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                    {{ auth()->user()->dietician_number }}
                                </span>
                                on the official HPCSA iRegister to confirm your registration is <strong>active and valid</strong>.
                            </p>
                        </div>
                    </li>

                    {{-- Step 2 --}}
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 w-6 h-6 rounded-full bg-amber-300 text-amber-900 text-xs font-bold flex items-center justify-center flex-shrink-0 shadow-sm">2</span>
                        <div>
                            <p class="font-semibold text-amber-900 mb-1">Account unlocked</p>
                            <p class="leading-relaxed">
                                Once confirmed, your account is activated — typically within <strong>30 minutes</strong> during business hours.
                            </p>
                        </div>
                    </li>

                    {{-- Step 3 --}}
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 w-6 h-6 rounded-full bg-green-400 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 shadow-sm">✓</span>
                        <div>
                            <p class="font-semibold text-green-800 mb-1">You'll receive a verification email</p>
                            <p class="leading-relaxed text-green-900">
                                A confirmation email will be sent to
                                <strong class="break-all">{{ auth()->user()->email }}</strong>
                                the moment your account is unlocked. Open it and click <em>"Log In"</em> to get started right away.
                            </p>
                        </div>
                    </li>

                </ol>
            </div>

            <p class="text-sm text-gray-500 mb-8">
                Questions? Contact us at
                <a href="mailto:support@mindfulnutrico.co.za"
                   class="text-[#1e5c3d] font-semibold hover:underline">
                    support@mindfulnutrico.co.za
                </a>
                and quote your DT number.
            </p>

            {{-- Log out --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-gray-400 hover:text-gray-600 underline">
                    Sign out
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
