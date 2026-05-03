<x-app-layout>
<style>
.rs-page { max-width:900px; margin:0 auto; padding:1.75rem 1.25rem 3rem; }
.rs-back { display:inline-flex; align-items:center; gap:.4rem; font-size:.8rem; font-weight:600; color:var(--text-muted); text-decoration:none; margin-bottom:1rem; }
.rs-back:hover { color:var(--primary); }

/* ── Layout ── */
.rs-layout { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }
@media(max-width:720px) { .rs-layout { grid-template-columns:1fr; } }

/* ── Recipe card ── */
.rs-card { background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.rs-image { width:100%; max-height:260px; object-fit:cover; display:block; }
.rs-image-ph { width:100%; height:180px; background:linear-gradient(135deg,#dcfce7,#d1fae5); display:flex; align-items:center; justify-content:center; font-size:4rem; }
.rs-body { padding:1.25rem 1.5rem; }
.rs-name { font-size:1.3rem; font-weight:800; color:var(--text-primary); margin:0 0 .4rem; }
.rs-desc { font-size:.85rem; color:var(--text-muted); line-height:1.6; margin:0 0 1rem; }
.rs-source { font-size:.75rem; color:#64748b; margin-bottom:1rem; }
.rs-source a { color:var(--primary); }

/* ── Macros row ── */
.rs-macros { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.rs-macro-chip { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:.5rem .75rem; text-align:center; min-width:68px; }
.rs-macro-chip .val { font-size:1.1rem; font-weight:800; color:#15803d; display:block; }
.rs-macro-chip .lbl { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#86efac; display:block; margin-top:2px; }

/* ── Sections ── */
.rs-section-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:#94a3b8; padding-top:1rem; border-top:1px solid #f1ede4; margin-bottom:.75rem; }
.rs-ingredients { padding:0 0 0 1.25rem; margin:0 0 1rem; font-size:.88rem; line-height:1.9; color:#374151; }
.rs-directions { list-style:none; padding:0; margin:0 0 1rem; }
.rs-direction { display:flex; gap:.75rem; margin-bottom:.85rem; }
.rs-dir-num { width:24px; height:24px; border-radius:50%; background:var(--primary); color:#fff; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
.rs-dir-text { font-size:.87rem; line-height:1.65; color:#374151; }

/* ── Flash ── */
.rs-flash { padding:.65rem 1rem; background:#dcfce7; color:#15803d; border-radius:8px; font-size:.82rem; font-weight:600; margin-bottom:1.1rem; border:1px solid #86efac; }
.rs-flash-err { background:#fee2e2; color:#b91c1c; border-color:#fca5a5; }

/* ── Send panel ── */
.rs-send-panel { background:#fff; border:1px solid var(--border); border-radius:14px; padding:1.25rem; position:sticky; top:80px; }
.rs-send-title { font-size:.95rem; font-weight:800; color:var(--text-primary); margin:0 0 .85rem; }
.rs-form-group { margin-bottom:.85rem; }
.rs-form-group label { display:block; font-size:.75rem; font-weight:700; color:var(--text-muted); margin-bottom:.3rem; }
.rs-form-group select,
.rs-form-group textarea {
    width:100%; padding:.5rem .75rem; border:1.5px solid var(--border);
    border-radius:8px; font-size:.85rem; outline:none; transition:border-color .15s;
    box-sizing:border-box;
}
.rs-form-group select:focus,
.rs-form-group textarea:focus { border-color:var(--primary); }
.rs-form-group textarea { resize:vertical; min-height:80px; }
.rs-send-btn {
    width:100%; padding:.6rem; background:var(--primary); color:#fff;
    border:none; border-radius:8px; font-size:.87rem; font-weight:700;
    cursor:pointer; transition:filter .15s;
}
.rs-send-btn:hover { filter:brightness(.92); }

/* ── Sent history ── */
.rs-sent-history { margin-top:1.1rem; border-top:1px solid var(--border); padding-top:.85rem; }
.rs-sent-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:#94a3b8; margin-bottom:.5rem; }
.rs-sent-row { font-size:.75rem; color:var(--text-muted); padding:.25rem 0; border-bottom:1px dashed #f1ede4; }
.rs-sent-row:last-child { border-bottom:none; }

/* ── Danger zone ── */
.rs-danger { margin-top:1rem; padding-top:.85rem; border-top:1px solid #fee2e2; }
.rs-danger-btn { width:100%; padding:.5rem; background:#fee2e2; color:#b91c1c; border:none; border-radius:8px; font-size:.8rem; font-weight:700; cursor:pointer; }
.rs-danger-btn:hover { background:#fecaca; }
</style>

<div class="rs-page">
    <a href="{{ route('recipes.index') }}" class="rs-back">
        ← Back to Recipes
    </a>

    @if(session('success'))
        <div class="rs-flash">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rs-flash rs-flash-err">{{ session('error') }}</div>
    @endif

    <div class="rs-layout">

        {{-- ── Left: Recipe detail ── --}}
        <div class="rs-card">
            @if($recipe->image_url)
                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="rs-image">
            @else
                <div class="rs-image-ph">🍽️</div>
            @endif

            <div class="rs-body">
                <h1 class="rs-name">{{ $recipe->name }}</h1>

                @if($recipe->description)
                    <p class="rs-desc">{{ $recipe->description }}</p>
                @endif

                @if($recipe->source_url)
                    <p class="rs-source">Source: <a href="{{ $recipe->source_url }}" target="_blank" rel="noopener">View on FatSecret ↗</a></p>
                @endif

                {{-- Macros --}}
                @if($recipe->calories || $recipe->protein_g || $recipe->carbs_g || $recipe->fat_g)
                    <div class="rs-macros">
                        @if($recipe->calories)
                            <div class="rs-macro-chip">
                                <span class="val">{{ round($recipe->calories) }}</span>
                                <span class="lbl">kcal</span>
                            </div>
                        @endif
                        @if($recipe->protein_g)
                            <div class="rs-macro-chip">
                                <span class="val">{{ round($recipe->protein_g) }}g</span>
                                <span class="lbl">Protein</span>
                            </div>
                        @endif
                        @if($recipe->carbs_g)
                            <div class="rs-macro-chip">
                                <span class="val">{{ round($recipe->carbs_g) }}g</span>
                                <span class="lbl">Carbs</span>
                            </div>
                        @endif
                        @if($recipe->fat_g)
                            <div class="rs-macro-chip">
                                <span class="val">{{ round($recipe->fat_g) }}g</span>
                                <span class="lbl">Fat</span>
                            </div>
                        @endif
                        @if($recipe->fiber_g)
                            <div class="rs-macro-chip">
                                <span class="val">{{ round($recipe->fiber_g) }}g</span>
                                <span class="lbl">Fiber</span>
                            </div>
                        @endif
                    </div>
                    @if($recipe->serving_size)
                        <p style="font-size:.73rem;color:#94a3b8;margin:-8px 0 1rem">Per {{ $recipe->serving_size }}</p>
                    @endif
                @endif

                {{-- Ingredients --}}
                @if(!empty($recipe->ingredients))
                    <div class="rs-section-title">Ingredients</div>
                    <ul class="rs-ingredients">
                        @foreach($recipe->ingredients as $ing)
                            <li>{{ $ing }}</li>
                        @endforeach
                    </ul>
                @endif

                {{-- Directions --}}
                @if($recipe->directions)
                    <div class="rs-section-title">Directions</div>
                    <ol class="rs-directions">
                        @foreach(array_filter(explode("\n", $recipe->directions)) as $step)
                            <li class="rs-direction">
                                <div class="rs-dir-num">{{ $loop->iteration }}</div>
                                <div class="rs-dir-text">{{ $step }}</div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>

        {{-- ── Right: Send panel ── --}}
        <div>
            <div class="rs-send-panel">
                <div class="rs-send-title">📤 Send to Patient</div>

                <form method="POST" action="{{ route('recipes.send', $recipe) }}">
                    @csrf
                    <div class="rs-form-group">
                        <label for="patient_id">Patient</label>
                        <select name="patient_id" id="patient_id" required>
                            <option value="">— Select patient —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                    @if(!$p->email) (no email) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<div style="color:#b91c1c;font-size:.72rem;margin-top:.2rem">{{ $message }}</div>@enderror
                    </div>

                    <div class="rs-form-group">
                        <label for="note">Note to patient (optional)</label>
                        <textarea name="note" id="note" placeholder="e.g. Great high-protein option for your meal plan…">{{ old('note') }}</textarea>
                        @error('note')<div style="color:#b91c1c;font-size:.72rem;margin-top:.2rem">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="rs-send-btn">Send Recipe via Email</button>
                </form>

                {{-- Send history --}}
                @php $sentPatients = $recipe->patients()->orderByPivot('sent_at', 'desc')->limit(10)->get(); @endphp
                @if($sentPatients->count())
                    <div class="rs-sent-history">
                        <div class="rs-sent-title">Previously sent to</div>
                        @foreach($sentPatients as $sp)
                            <div class="rs-sent-row">
                                {{ $sp->name }}
                                @if($sp->pivot->sent_at)
                                    · {{ \Carbon\Carbon::parse($sp->pivot->sent_at)->format('d M Y') }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Delete --}}
                <div class="rs-danger">
                    <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" onsubmit="return confirm('Delete this recipe from your library?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="rs-danger-btn">Delete Recipe</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</x-app-layout>
