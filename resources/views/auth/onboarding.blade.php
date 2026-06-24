<x-guest-layout>
    {{-- Step progress --}}
    <div class="step-progress">
        @for ($i = 1; $i <= $totalSteps; $i++)
            <div class="step-pip {{ $i < $step ? 'done' : '' }} {{ $i === $step ? 'active' : '' }}"></div>
        @endfor
    </div>

    <div style="margin-bottom:1.75rem">
        <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step {{ $step }} of {{ $totalSteps }}</p>
        <h1 class="auth-card-title">
            @switch($step)
                @case(1) Welcome! What's your name? @break
                @case(2) Professional ID @break
            @endswitch
        </h1>
        <p class="auth-card-sub">
            @switch($step)
                @case(1) Let us know what to call you. @break
                @case(2) Enter your HPCSA registered dietitian number. @break
            @endswitch
        </p>
    </div>

    <form method="POST" action="{{ route('onboarding.store', ['step' => $step]) }}" style="width:100%">
        @csrf

        @if ($step === 1)
            <div class="auth-field">
                <label class="auth-label" for="name">Full Name</label>
                <div class="auth-input-wrap">
                    <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="auth-input" required autofocus autocomplete="name" placeholder="Dr. Jane Smith"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                @error('name')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        @endif

        @if ($step === 2)
            <div class="auth-field">
                <label class="auth-label" for="dietician_number">HPCSA Registration Number</label>
                <div class="auth-input-wrap">
                    <input id="dietician_number" type="text" name="dietician_number" value="{{ old('dietician_number', auth()->user()->dietician_number) }}" class="auth-input" required autofocus autocomplete="off" placeholder="DT0012345" pattern="[Dd][Tt]\d{7}" maxlength="9" style="text-transform:uppercase;letter-spacing:.08em;font-weight:600" oninput="formatDtNumber(this)"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1m-4 0h4"/></svg>
                </div>
                <p style="margin-top:.35rem;font-size:.75rem;color:var(--text-muted)">Format: DT followed by 7 digits, e.g. DT0012345</p>
                @error('dietician_number')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        @endif

        {{-- Navigation --}}
        <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-top:1.5rem">
            @if ($step > 1)
                <a href="{{ route('onboarding.show', ['step' => $step - 1]) }}" class="auth-btn-ghost" style="width:auto;padding:.75rem 1.25rem;text-decoration:none;text-align:center">
                    &larr; Back
                </a>
            @endif

            <button type="submit" class="auth-btn" style="flex:1">
                {!! $step === $totalSteps ? 'Finish Setup &#10003;' : 'Continue &rarr;' !!}
            </button>
        </div>

    </form>

    {{-- Skip (outside the step form) --}}
    <div style="text-align:center;margin-top:1.25rem">
        <form method="POST" action="{{ route('onboarding.skip') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--text-muted);font-size:.82rem;cursor:pointer;text-decoration:underline">
                Skip for now
            </button>
        </form>
    </div>

    <script>
    function formatDtNumber(input) {
        var v = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (v.length > 9) v = v.slice(0, 9);
        input.value = v;
    }
    </script>
</x-guest-layout>
