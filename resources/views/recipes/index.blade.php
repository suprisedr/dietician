<x-app-layout>
<style>
/* ── Page layout ── */
.rcp-page { max-width:1200px; margin:0 auto; padding:1.75rem 1.25rem 3rem; }

/* ── Header ── */
.rcp-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.rcp-title   { font-size:1.35rem; font-weight:800; color:var(--text-primary); margin:0 0 .2rem; }
.rcp-subtitle { font-size:.82rem; color:var(--text-muted); margin:0; }

/* ── Flash ── */
.rcp-flash { padding:.65rem 1rem; background:#dcfce7; color:#15803d; border-radius:8px; font-size:.82rem; font-weight:600; margin-bottom:1.1rem; border:1px solid #86efac; }

/* ── Search box ── */
.rcp-search-wrap { position:relative; margin-bottom:1.25rem; }
.rcp-search-input {
    width:100%; padding:.6rem 2.8rem .6rem .9rem;
    border:1.5px solid var(--border); border-radius:8px;
    font-size:.92rem; outline:none; transition:border-color .15s;
    box-sizing:border-box; background:#fff;
}
.rcp-search-input:focus { border-color:var(--primary); }
.rcp-search-clear {
    position:absolute; right:.65rem; top:50%; transform:translateY(-50%);
    font-size:1rem; color:#94a3b8; cursor:pointer; display:none;
    background:none; border:none; line-height:1; padding:.15rem;
}
.rcp-search-spinner {
    position:absolute; right:2.4rem; top:50%; transform:translateY(-50%);
    width:16px; height:16px; border:2px solid #d1fae5; border-top-color:var(--primary);
    border-radius:50%; animation:spin .6s linear infinite; display:none;
}
@keyframes spin { to { transform:translateY(-50%) rotate(360deg); } }

/* ── Section heading ── */
.rcp-section-head {
    display:flex; align-items:center; gap:.5rem;
    font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em;
    color:#94a3b8; margin:.4rem 0 .65rem; padding-bottom:.4rem;
    border-bottom:1px solid var(--border);
}
.rcp-section-badge {
    background:#e2e8f0; color:#64748b; border-radius:999px;
    font-size:.65rem; font-weight:700; padding:.1rem .45rem;
}

/* ── Recipe card grid ── */
.rcp-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1rem; }

/* ── Recipe card ── */
.rcp-card {
    background:#fff; border:1px solid var(--border); border-radius:12px;
    overflow:hidden; transition:box-shadow .2s,transform .15s; cursor:pointer;
    display:flex; flex-direction:column;
}
.rcp-card:hover { box-shadow:0 6px 24px rgba(13,31,12,.12); transform:translateY(-2px); }

.rcp-card-img { width:100%; height:140px; object-fit:cover; background:#f1f5f9; display:block; }
.rcp-card-img-placeholder { width:100%; height:140px; background:linear-gradient(135deg,#dcfce7,#d1fae5); display:flex; align-items:center; justify-content:center; font-size:2.5rem; }

.rcp-card-body { padding:.85rem 1rem 1rem; flex:1; display:flex; flex-direction:column; gap:.4rem; }
.rcp-card-name { font-size:.95rem; font-weight:700; color:var(--text-primary); line-height:1.3; }
.rcp-card-desc { font-size:.75rem; color:var(--text-muted); line-height:1.4; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }

.rcp-card-macros { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.2rem; }
.rcp-macro-chip {
    font-size:.68rem; font-weight:700; padding:.18rem .5rem;
    border-radius:999px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;
}

.rcp-card-footer { padding:.6rem 1rem .85rem; display:flex; gap:.5rem; align-items:center; border-top:1px solid #f1f5f9; }
.rcp-btn-save {
    flex:1; background:var(--primary); color:#fff; border:none; border-radius:7px;
    font-size:.75rem; font-weight:700; padding:.4rem .8rem; cursor:pointer;
    transition:filter .15s;
}
.rcp-btn-save:hover { filter:brightness(.92); }
.rcp-btn-save:disabled { opacity:.5; cursor:not-allowed; filter:none; }
.rcp-btn-view {
    padding:.4rem .8rem; background:#f8fafc; color:#374151; border:1px solid var(--border);
    border-radius:7px; font-size:.75rem; font-weight:600; text-decoration:none;
    display:inline-flex; align-items:center; white-space:nowrap;
}
.rcp-btn-view:hover { background:#f1f5f9; }

/* ── Saved recipes list ── */
.rcp-saved-list { display:flex; flex-direction:column; gap:.6rem; }
.rcp-saved-row {
    display:flex; align-items:center; gap:1rem; padding:.75rem 1rem;
    background:#fff; border:1px solid var(--border); border-radius:10px;
    transition:box-shadow .15s;
}
.rcp-saved-row:hover { box-shadow:0 3px 12px rgba(13,31,12,.08); }
.rcp-saved-thumb { width:52px; height:52px; border-radius:8px; object-fit:cover; flex-shrink:0; }
.rcp-saved-thumb-ph { width:52px; height:52px; border-radius:8px; background:#dcfce7; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; }
.rcp-saved-info { flex:1; min-width:0; }
.rcp-saved-name { font-size:.9rem; font-weight:700; color:var(--text-primary); }
.rcp-saved-macros { font-size:.73rem; color:var(--text-muted); margin-top:.15rem; }
.rcp-saved-actions { display:flex; gap:.4rem; align-items:center; }
.rcp-saved-view  { padding:.35rem .8rem; background:var(--primary); color:#fff; border-radius:7px; font-size:.75rem; font-weight:700; text-decoration:none; }
.rcp-saved-del   { padding:.35rem .7rem; background:#fee2e2; color:#b91c1c; border:none; border-radius:7px; font-size:.75rem; font-weight:700; cursor:pointer; }
.rcp-saved-del:hover { background:#fecaca; }

/* ── Empty state ── */
.rcp-empty { text-align:center; padding:2.5rem 1rem; color:var(--text-muted); font-size:.88rem; }

/* ── Saving toast ── */
#rcp-toast {
    position:fixed; bottom:1.5rem; right:1.5rem; z-index:999;
    background:#16a34a; color:#fff; font-size:.82rem; font-weight:700;
    padding:.6rem 1.1rem; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,.18);
    display:none; animation:slideUp .3s ease;
}
@keyframes slideUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

/* ── Pagination ── */
.rcp-pagination { margin-top:1.5rem; display:flex; justify-content:center; }
</style>

<div class="rcp-page">

    {{-- Header --}}
    <div class="rcp-header">
        <div>
            <h1 class="rcp-title">Recipes</h1>
            <p class="rcp-subtitle">Search FatSecret recipes and save them to your library. Send recipes directly to patients.</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rcp-flash">{{ session('success') }}</div>
    @endif

    {{-- Live search input --}}
    <div class="rcp-search-wrap">
        <input id="rcp-search"
               class="rcp-search-input"
               type="search"
               autocomplete="off"
               placeholder="Search recipes (e.g. grilled chicken, smoothie, oats…)"
               value="{{ $search }}">
        <div class="rcp-search-spinner" id="rcp-spinner"></div>
        <button class="rcp-search-clear" id="rcp-clear" title="Clear">✕</button>
    </div>

    {{-- ── Live search results (shown while typing) ── --}}
    <div id="rcp-live-results" style="display:none">
        {{-- DB results --}}
        <div id="rcp-db-section" style="display:none">
            <div class="rcp-section-head">
                Saved in Your Library
                <span class="rcp-section-badge" id="rcp-db-count">0</span>
            </div>
            <div class="rcp-grid" id="rcp-db-grid"></div>
        </div>

        {{-- FatSecret results --}}
        <div id="rcp-fs-section" style="display:none">
            <div class="rcp-grid" id="rcp-fs-grid"></div>
        </div>

        <div id="rcp-no-results" style="display:none" class="rcp-empty">
            No recipes found for that search. Try different keywords.
        </div>
    </div>

    {{-- ── Saved library (shown when not searching) ── --}}
    <div id="rcp-library">
        @if($recipes->count())
            <div class="rcp-section-head">
                Your Saved Recipes
                <span class="rcp-section-badge">{{ $recipes->total() }}</span>
            </div>
            <div class="rcp-saved-list">
                @foreach($recipes as $r)
                    <div class="rcp-saved-row">
                        @if($r->image_url)
                            <img src="{{ $r->image_url }}" alt="{{ $r->name }}" class="rcp-saved-thumb">
                        @else
                            <div class="rcp-saved-thumb-ph">🍽️</div>
                        @endif
                        <div class="rcp-saved-info">
                            <div class="rcp-saved-name">{{ $r->name }}</div>
                            <div class="rcp-saved-macros">
                                @if($r->calories) {{ round($r->calories) }} kcal @endif
                                @if($r->protein_g) · {{ round($r->protein_g) }}g protein @endif
                                @if($r->carbs_g) · {{ round($r->carbs_g) }}g carbs @endif
                                @if($r->fat_g) · {{ round($r->fat_g) }}g fat @endif
                                @if(!$r->calories && !$r->protein_g) <span style="color:#cbd5e1">No nutrition info</span> @endif
                            </div>
                        </div>
                        <div class="rcp-saved-actions">
                            <a href="{{ route('recipes.show', $r) }}" class="rcp-saved-view">View & Send</a>
                            <form method="POST" action="{{ route('recipes.destroy', $r) }}" onsubmit="return confirm('Delete this recipe?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rcp-saved-del">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="rcp-pagination">{{ $recipes->links() }}</div>
        @else
            <div class="rcp-empty">
                <div style="font-size:2.5rem;margin-bottom:.5rem">🍽️</div>
                <div style="font-weight:700;color:var(--text-primary);margin-bottom:.3rem">No saved recipes yet</div>
                <div>Search above to find recipes from FatSecret and save them to your library.</div>
            </div>
        @endif
    </div>

</div>

<div id="rcp-toast"></div>

<script>
(function () {
    const input    = document.getElementById('rcp-search');
    const clearBtn = document.getElementById('rcp-clear');
    const spinner  = document.getElementById('rcp-spinner');
    const liveWrap = document.getElementById('rcp-live-results');
    const library  = document.getElementById('rcp-library');
    const dbSection = document.getElementById('rcp-db-section');
    const fsSection = document.getElementById('rcp-fs-section');
    const dbGrid   = document.getElementById('rcp-db-grid');
    const fsGrid   = document.getElementById('rcp-fs-grid');
    const dbCount  = document.getElementById('rcp-db-count');
    const noResults = document.getElementById('rcp-no-results');
    const toast    = document.getElementById('rcp-toast');

    let debounce, currentXhr = null;

    const searchUrl  = "{{ route('recipes.search') }}";
    const importUrl  = "{{ route('recipes.import-fatsecret') }}";
    const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.content;

    // ── Show/hide clear button ───────────────────────────────────────────────
    function updateClear() {
        clearBtn.style.display = input.value ? 'block' : 'none';
    }
    clearBtn.addEventListener('click', () => {
        input.value = '';
        updateClear();
        hideResults();
    });
    input.addEventListener('input', updateClear);
    updateClear();

    // ── On input: debounce 300ms then fire search ────────────────────────────
    input.addEventListener('input', () => {
        clearTimeout(debounce);
        const q = input.value.trim();
        if (!q) { hideResults(); return; }
        debounce = setTimeout(() => doSearch(q), 300);
    });

    function hideResults() {
        liveWrap.style.display = 'none';
        library.style.display  = '';
    }

    function doSearch(q) {
        if (currentXhr) currentXhr.abort();
        spinner.style.display = 'block';
        liveWrap.style.display = '';
        library.style.display  = 'none';
        dbSection.style.display = 'none';
        fsSection.style.display = 'none';
        noResults.style.display = 'none';
        dbGrid.innerHTML = '';
        fsGrid.innerHTML = '';

        const ctrl = new AbortController();
        currentXhr = ctrl;

        fetch(searchUrl + '?q=' + encodeURIComponent(q), { signal: ctrl.signal })
            .then(r => r.json())
            .then(data => {
                spinner.style.display = 'none';
                currentXhr = null;
                renderResults(data);
            })
            .catch(err => {
                if (err.name !== 'AbortError') spinner.style.display = 'none';
            });
    }

    function renderResults(data) {
        const db = data.db || [];
        const fs = data.fs || [];

        if (!db.length && !fs.length) {
            noResults.style.display = '';
            return;
        }

        // ── DB recipes ───────────────────────────────────────────────────────
        if (db.length) {
            dbCount.textContent = db.length;
            db.forEach(r => dbGrid.appendChild(makeCard(r, true)));
            dbSection.style.display = '';
        }

        // ── FatSecret recipes ─────────────────────────────────────────────────
        if (fs.length) {
            fs.forEach(r => fsGrid.appendChild(makeCard(r, false)));
            fsSection.style.display = '';
        }
    }

    function makeCard(r, isSaved) {
        const div = document.createElement('div');
        div.className = 'rcp-card';

        const macros = [];
        if (r.calories) macros.push(Math.round(r.calories) + ' kcal');
        if (r.protein)  macros.push(Math.round(r.protein) + 'g protein');
        if (r.carbs)    macros.push(Math.round(r.carbs) + 'g carbs');
        if (r.fat)      macros.push(Math.round(r.fat) + 'g fat');
        const macroHtml = macros.map(m => `<span class="rcp-macro-chip">${m}</span>`).join('');

        const imgHtml = r.image
            ? `<img src="${esc(r.image)}" alt="${esc(r.name)}" class="rcp-card-img">`
            : `<div class="rcp-card-img-placeholder">🍽️</div>`;

        const descText = r.description ? `<div class="rcp-card-desc">${esc(r.description)}</div>` : '';

        let footerHtml = '';
        if (isSaved) {
            footerHtml = `
                <div class="rcp-card-footer">
                    <a href="/recipes/${r.id}" class="rcp-btn-view">View &amp; Send</a>
                </div>`;
        } else {
            footerHtml = `
                <div class="rcp-card-footer">
                    <button class="rcp-btn-save" data-id="${esc(r.fs_id)}" data-name="${esc(r.name)}"
                            data-desc="${esc(r.description || '')}" data-img="${esc(r.image || '')}"
                            data-url="${esc(r.source_url || '')}">
                        + Save Recipe
                    </button>
                </div>`;
        }

        div.innerHTML = `
            ${imgHtml}
            <div class="rcp-card-body">
                <div class="rcp-card-name">${esc(r.name)}</div>
                ${descText}
                <div class="rcp-card-macros">${macroHtml}</div>
            </div>
            ${footerHtml}
        `;

        // Save button handler
        if (!isSaved) {
            const btn = div.querySelector('.rcp-btn-save');
            btn.addEventListener('click', () => saveRecipe(btn, r));
        }

        return div;
    }

    function saveRecipe(btn, r) {
        btn.disabled = true;
        btn.textContent = 'Saving…';

        fetch(importUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                fatsecret_recipe_id: r.fs_id,
                name:        r.name,
                description: r.description || null,
                image_url:   r.image       || null,
                source_url:  r.source_url  || null,
            }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.id) {
                btn.textContent = '✓ Saved';
                btn.style.background = '#15803d';
                showToast('Recipe saved! ' + (data.already_existed ? '(already in library)' : ''));
                // Add "View" link next to button
                const viewLink = document.createElement('a');
                viewLink.href = data.url;
                viewLink.className = 'rcp-btn-view';
                viewLink.textContent = 'View & Send';
                btn.parentNode.insertBefore(viewLink, btn.nextSibling);
            } else {
                btn.disabled = false;
                btn.textContent = '+ Save Recipe';
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = '+ Save Recipe';
        });
    }

    function showToast(msg) {
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }
})();
</script>
</x-app-layout>
