<section x-data="letterheadPreview()" x-init="init()">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('PDF Letterhead') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload a letterhead image (PNG or JPG) to appear at the top of all generated PDF documents — food diaries, meal plans, and patient reports.') }}
        </p>
    </header>

    {{-- ── PDF simulation preview ────────────────────────────────── --}}
    <div class="mt-6">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <p class="text-sm font-medium text-gray-700">PDF Preview</p>
            <span x-show="pendingFile" x-transition
                  style="font-size:.72rem;font-weight:600;padding:2px 8px;background:#fef9c3;color:#854d0e;border-radius:999px;border:1px solid #fde68a">
                Unsaved — click Save to apply
            </span>
        </div>

        {{-- Simulated A4 page strip --}}
        <div style="background:#f3e9e9;border:1.5px solid #d1d5db;border-radius:8px;overflow:hidden;max-width:560px;box-shadow:0 2px 8px rgba(0,0,0,.08)">

            {{-- Letterhead zone --}}
            <div style="background:#f3e9e9;border-bottom:1.5px solid #c8ddd6;padding:10px 12px;min-height:60px;display:flex;align-items:center;justify-content:center">
                <img id="lh-preview-img"
                     :src="previewSrc"
                     x-show="previewSrc"
                     alt="Letterhead"
                     style="max-height:90px;width:100%;object-fit:contain;display:block">
                <span x-show="!previewSrc"
                      style="font-size:.75rem;color:#9ca3af;font-style:italic">
                    No letterhead — document title will appear here
                </span>
            </div>

            {{-- Simulated document content lines --}}
            <div style="padding:12px 14px">
                <div style="display:flex;justify-content:space-between;align-items:baseline;border-bottom:2px solid #2d5a43;padding-bottom:6px;margin-bottom:10px">
                    <div>
                        <div style="font-size:9px;color:#2d5a43;font-style:italic">Daily food</div>
                        <div style="font-size:12px;font-weight:bold;color:#2d5a43;letter-spacing:2px">DIARY</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:7px;color:#2d5a43;font-weight:bold;border-bottom:1px solid #2d5a43;padding-bottom:1px;margin-bottom:2px">Date: 20 Apr 2026</div>
                        <div style="font-size:7px;color:#2d5a43;font-weight:bold">Day: Monday</div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                    @foreach(['Breakfast','Snack (Morning)','Lunch','Snack (Afternoon)'] as $s)
                    <div style="border:1px solid #2d5a43">
                        <div style="background:#2d5a43;padding:2px 6px;font-size:6px;color:#fff;font-weight:bold;letter-spacing:.5px">{{ strtoupper($s) }}</div>
                        <div style="height:14px;background:#faf0f0"></div>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:6px;color:#9ca3af;text-align:right;margin-top:8px;border-top:1px solid #c8ddd6;padding-top:3px">
                    {{ config('app.name') }} · Confidential · For clinical use only
                </p>
            </div>
        </div>

        <p class="mt-2 text-xs text-gray-400">
            This is a live preview of how your letterhead appears on PDF documents.
        </p>
    </div>

    {{-- ── Upload / Remove controls ─────────────────────────────── --}}
    <form method="POST" action="{{ route('profile.letterhead.update') }}"
          enctype="multipart/form-data" class="mt-5 space-y-4"
          @submit="pendingFile = false">
        @csrf

        <div>
            <label for="letterhead"
                   style="display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem">
                {{ $user->letterhead_path ? 'Replace letterhead' : 'Upload letterhead' }}
            </label>

            {{-- Custom drop-zone --}}
            <label for="letterhead"
                   @dragover.prevent="dragging = true"
                   @dragleave.prevent="dragging = false"
                   @drop.prevent="onDrop($event)"
                   :style="dragging ? 'border-color:#2d5a43;background:#f0faf5' : ''"
                   style="display:flex;align-items:center;gap:.75rem;padding:.6rem .9rem;border:1.5px dashed #d1d5db;border-radius:8px;cursor:pointer;transition:all .15s;background:#fafaf9;max-width:420px">
                <svg style="width:20px;height:20px;color:#6b7280;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4-4m0 0l4 4m-4-4v12M20 16v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2M16 8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span style="font-size:.8rem;color:#6b7280">
                    <span x-text="fileName || 'Click or drag &amp; drop an image here'"></span>
                </span>
            </label>

            <input id="letterhead" name="letterhead" type="file"
                   accept="image/png,image/jpeg,image/jpg"
                   @change="onFileChange($event)"
                   style="display:none">

            <p class="mt-2 text-xs text-gray-400">PNG or JPG · Max 3 MB · Recommended width: 1200 px</p>
            <x-input-error class="mt-1" :messages="$errors->get('letterhead')" />
        </div>

        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
            <button type="submit"
                    :disabled="!pendingFile"
                    :style="pendingFile ? 'opacity:1;cursor:pointer' : 'opacity:.45;cursor:not-allowed'"
                    style="padding:.45rem 1.2rem;background:#2d5a43;color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;transition:opacity .15s">
                Save Letterhead
            </button>

            @if($user->letterhead_path)
            <form method="POST" action="{{ route('profile.letterhead.remove') }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Remove letterhead from all PDFs?')"
                        style="font-size:.8rem;color:#dc2626;background:none;border:none;cursor:pointer;text-decoration:underline;padding:0">
                    Remove letterhead
                </button>
            </form>
            @endif

            @if(session('status') === 'letterhead-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   style="font-size:.8rem;color:#16a34a;font-weight:600">
                    ✓ Letterhead saved.
                </p>
            @endif

            @if(session('status') === 'letterhead-removed')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   style="font-size:.8rem;color:#6b7280">
                    Letterhead removed.
                </p>
            @endif
        </div>
    </form>
</section>

<script>
function letterheadPreview() {
    return {
        previewSrc: @json($user->letterhead_path ? route('profile.letterhead.preview') : null),
        fileName:   null,
        pendingFile: false,
        dragging:   false,

        init() {
            // nothing extra needed — previewSrc is set from server
        },

        onFileChange(e) {
            const file = e.target.files[0];
            if (file) this.loadFile(file);
        },

        onDrop(e) {
            this.dragging = false;
            const file = e.dataTransfer.files[0];
            if (!file) return;
            // Sync to hidden input
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('letterhead').files = dt.files;
            this.loadFile(file);
        },

        loadFile(file) {
            if (!file.type.startsWith('image/')) return;
            this.fileName    = file.name;
            this.pendingFile = true;
            const reader = new FileReader();
            reader.onload = (ev) => { this.previewSrc = ev.target.result; };
            reader.readAsDataURL(file);
        }
    };
}
</script>
