<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">&#x1F4D3; Food Diaries</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Daily food records for your patients</p>
            </div>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                <a href="{{ route('food-diary.weekly') }}"
                   style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:#2d5a43;color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                    &#x1F4C5; Weekly View
                </a>
                <a href="{{ route('food-diary.create') }}"
                   style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                    + New Entry (Manual)
                </a>
            </div>
        </div>

        {{-- Send invite panel --}}
        @php
            $patients  = \App\Models\Patient::where('user_id', auth()->id())->orderBy('name')->get(['id','name','surname','email']);
            $monday    = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d');
        @endphp
        <div style="background:#f0f9f4;border:1px solid #b7dfc9;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.25rem">
            <div style="font-size:.82rem;font-weight:700;color:#1a3d2b;margin-bottom:.75rem;display:flex;align-items:center;gap:.4rem">
                &#x2709; Send diary link to a patient
            </div>
            <form method="POST" action="{{ route('food-diary.send-invite') }}" id="invite-form"
                  style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:flex-start">
                @csrf

                {{-- Patient --}}
                <select name="patient_id" required
                        style="flex:1;min-width:200px;padding:.42rem .7rem;border:1px solid #b7dfc9;border-radius:6px;font-size:.83rem;background:#fff;color:var(--text-primary)">
                    <option value="">— Select a patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" @if(!$p->email) disabled @endif>
                            {{ $p->name }} {{ $p->surname }}{{ !$p->email ? ' (no email)' : '' }}
                        </option>
                    @endforeach
                </select>

                {{-- Type dropdown --}}
                <select name="diary_type" id="diary-type-select" required
                        onchange="toggleWeekPicker(this.value)"
                        style="padding:.42rem .7rem;border:1px solid #b7dfc9;border-radius:6px;font-size:.83rem;background:#fff;color:var(--text-primary);min-width:148px">
                    <option value="daily">&#x1F4D3; Daily Diary</option>
                    <option value="weekly">&#x1F4C5; Weekly Diary</option>
                </select>

                {{-- Week picker (shown only for weekly) --}}
                <div id="week-picker-wrap" style="display:none;align-items:center;gap:.35rem">
                    <label style="font-size:.75rem;font-weight:700;color:#2d5a43;white-space:nowrap">Week of</label>
                    <input type="date" name="week_start" id="week-start-input"
                           value="{{ $monday }}"
                           style="padding:.4rem .6rem;border:1px solid #b7dfc9;border-radius:6px;font-size:.83rem;background:#fff;color:var(--text-primary)">
                </div>

                <button type="submit"
                        style="padding:.42rem 1.1rem;background:#2d5a43;color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer;white-space:nowrap">
                    &#x1F4E7; Send Link
                </button>
            </form>
        </div>

        <script>
        function toggleWeekPicker(val) {
            var wrap = document.getElementById('week-picker-wrap');
            wrap.style.display = val === 'weekly' ? 'flex' : 'none';
        }
        </script>

        {{-- Search --}}
        <form method="GET" style="margin-bottom:1.1rem;display:flex;gap:.5rem">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search patient name..."
                   style="flex:1;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem">
            <button type="submit" style="padding:.5rem 1rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">Search</button>
            @if($search)
                <a href="{{ route('food-diary.index') }}" style="padding:.5rem 1rem;background:#f1f5f9;color:var(--text-primary);font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Clear</a>
            @endif
        </form>

        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">&#x2713; {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="padding:.65rem 1rem;background:#fee2e2;color:#b91c1c;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">&#x26A0; {{ session('error') }}</div>
        @endif

        @if($diaries->isEmpty())
            <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:1rem">&#x1F4D3;</div>
                <p style="font-weight:600;margin:0 0 .5rem">No diary entries yet</p>
                <p style="font-size:.82rem;margin-bottom:1rem">Send a diary link to a patient above, or add one manually.</p>
            </div>
        @else
            <div style="display:grid;gap:.75rem">
                @foreach($diaries as $diary)
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:.85rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                        <div>
                            <div style="font-weight:700;color:var(--text-primary);font-size:.9rem;display:flex;align-items:center;gap:.5rem">
                                @if($diary->submitted_at)
                                    {{ $diary->diary_date?->format('l, d M Y') ?? '—' }}
                                    <span style="background:#dcfce7;color:#15803d;font-size:.68rem;font-weight:700;padding:.1rem .45rem;border-radius:20px">&#x2713; Submitted</span>
                                @elseif($diary->patient_token && !$diary->diary_date)
                                    <span style="color:var(--text-muted);font-style:italic">Pending patient response</span>
                                    <span style="background:#fef9c3;color:#854d0e;font-size:.68rem;font-weight:700;padding:.1rem .45rem;border-radius:20px">&#x23F3; Awaiting</span>
                                @else
                                    {{ $diary->diary_date?->format('l, d M Y') ?? '—' }}
                                @endif
                            </div>
                            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.25rem;display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
                                @if($diary->patient)
                                    <span style="background:#ffedd5;color:#c2410c;font-weight:700;padding:.1rem .45rem;border-radius:20px">
                                        &#x1F464; {{ $diary->patient->name }}
                                    </span>
                                @endif
                                @if($diary->rating)
                                    <span style="background:#fef9c3;color:#854d0e;font-weight:700;padding:.1rem .45rem;border-radius:20px">
                                        &#9733; {{ $diary->rating }}/5
                                    </span>
                                @endif
                                <span>{{ $diary->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:.35rem;flex-shrink:0;flex-wrap:wrap">
                            @if($diary->submitted_at || $diary->diary_date)
                                <a href="{{ route('food-diary.show', $diary) }}"
                                   style="padding:.28rem .7rem;background:var(--primary);color:#fff;font-size:.73rem;font-weight:700;border-radius:5px;text-decoration:none">View</a>
                                <a href="{{ route('food-diary.edit', $diary) }}"
                                   style="padding:.28rem .7rem;background:#f1f5f9;color:var(--text-primary);font-size:.73rem;font-weight:700;border-radius:5px;text-decoration:none">Edit</a>
                                <button type="button"
                                        onclick="openPdfPreviewModal('{{ route('food-diary.pdf', $diary) }}?stream=1','{{ route('food-diary.pdf', $diary) }}')"
                                        style="padding:.28rem .7rem;background:#e0e7ff;color:#3730a3;font-size:.73rem;font-weight:700;border-radius:5px;border:none;cursor:pointer">PDF</button>
                            @endif
                            @if($diary->patient_token && !$diary->submitted_at)
                                <span title="{{ route('food-diary.patient-show', $diary->patient_token) }}"
                                      style="padding:.28rem .7rem;background:#fef9c3;color:#854d0e;font-size:.73rem;font-weight:700;border-radius:5px;cursor:default">
                                    &#x23F3; Pending
                                </span>
                            @endif
                            <form method="POST" action="{{ route('food-diary.destroy', $diary) }}"
                                  onsubmit="return confirm('Delete this diary entry?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="padding:.28rem .7rem;background:#fee2e2;color:#b91c1c;font-size:.73rem;font-weight:700;border-radius:5px;border:none;cursor:pointer">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:1.25rem">
                {{ $diaries->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
