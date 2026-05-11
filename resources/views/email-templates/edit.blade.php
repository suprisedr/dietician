<x-app-layout>

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('email-templates.index') }}"
                   style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:50%;background:rgba(255,255,255,.18);color:#fff;text-decoration:none;flex-shrink:0;transition:background .15s"
                   title="Back">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        Email Templates / Edit
                    </p>
                    <h1>{{ $meta['label'] }}</h1>
                    <p>{{ $meta['description'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="emailEditor(@js($template->body_html ?? $meta['default_body']))">

        {{-- Flash messages --}}
        @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #86efac;border-left:4px solid #16a34a;border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.88rem;color:#166534;display:flex;align-items:center;gap:.6rem">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-left:4px solid #dc2626;border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.88rem;color:#991b1b">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('email-templates.update', $type) }}" @submit="syncContent">
            @csrf
            @method('PUT')

            <div class="grid gap-5">

                {{-- ── Subject + Heading ── --}}
                <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.5rem">
                    <h3 style="font-size:.9rem;font-weight:700;color:var(--text-primary);margin:0 0 1.1rem;display:flex;align-items:center;gap:.5rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email Identity
                    </h3>

                    <div class="grid gap-4">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">
                                Subject Line
                            </label>
                            <input type="text" name="subject"
                                   value="{{ old('subject', $template->subject ?? '') }}"
                                   placeholder="{{ $meta['default_subject'] }} (default)"
                                   style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary-dark)'"
                                   onblur="this.style.borderColor='var(--border)'">
                            <p style="font-size:.75rem;color:var(--text-muted);margin:.3rem 0 0">Leave blank to use the default subject. Supports {patient_name} and {dietician_name}.</p>
                        </div>

                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">
                                Email Heading
                            </label>
                            <input type="text" name="heading"
                                   value="{{ old('heading', $template->heading ?? '') }}"
                                   placeholder="{{ $meta['default_heading'] }} (default)"
                                   style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary-dark)'"
                                   onblur="this.style.borderColor='var(--border)'">
                            <p style="font-size:.75rem;color:var(--text-muted);margin:.3rem 0 0">The large title shown in the coloured email header.</p>
                        </div>
                    </div>
                </div>

                {{-- ── Body Editor ── --}}
                <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.5rem">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem">
                        <h3 style="font-size:.9rem;font-weight:700;color:var(--text-primary);margin:0;display:flex;align-items:center;gap:.5rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Body Content
                        </h3>
                        {{-- Merge tag chips --}}
                        <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                            <span style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Insert:</span>
                            @foreach(['{patient_name}' => 'patient_name', '{patient_full_name}' => 'patient_full_name', '{dietician_name}' => 'dietician_name'] as $label => $tag)
                            <button type="button"
                                    @click="insertTag('{{ $tag }}')"
                                    style="display:inline-flex;align-items:center;padding:.2rem .55rem;background:#f0f9f4;border:1px solid #b7dfc9;border-radius:5px;font-size:.75rem;font-weight:700;color:var(--primary-dark);cursor:pointer;transition:all .12s"
                                    onmouseover="this.style.background='#d4efe0'" onmouseout="this.style.background='#f0f9f4'">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Formatting toolbar --}}
                    <div style="display:flex;align-items:center;gap:.25rem;padding:.5rem .65rem;background:#f8fafc;border:1.5px solid var(--border);border-bottom:none;border-radius:8px 8px 0 0;flex-wrap:wrap">
                        <button type="button" @click="exec('bold')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;font-weight:800;font-size:.9rem;color:var(--text-primary);transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Bold">B</button>
                        <button type="button" @click="exec('italic')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;font-style:italic;font-size:.9rem;color:var(--text-primary);transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Italic">I</button>
                        <button type="button" @click="exec('underline')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;text-decoration:underline;font-size:.9rem;color:var(--text-primary);transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Underline">U</button>
                        <div style="width:1px;height:1.2rem;background:var(--border);margin:0 .2rem"></div>
                        <button type="button" @click="exec('insertUnorderedList')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Bullet list">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/><circle cx="2" cy="6" r="1" fill="currentColor"/><circle cx="2" cy="12" r="1" fill="currentColor"/><circle cx="2" cy="18" r="1" fill="currentColor"/></svg>
                        </button>
                        <button type="button" @click="exec('insertOrderedList')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Numbered list">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6h11M10 12h11M10 18h11M4 6h.01M4 12h.01M4 18h.01"/></svg>
                        </button>
                        <div style="width:1px;height:1.2rem;background:var(--border);margin:0 .2rem"></div>
                        <button type="button" @click="insertLink()"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Insert link">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button type="button" @click="exec('removeFormat')"
                                style="width:2rem;height:2rem;display:inline-flex;align-items:center;justify-content:center;border-radius:5px;background:none;border:none;cursor:pointer;transition:background .12s"
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='none'" title="Clear formatting">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Contenteditable editor --}}
                    <div id="email-body-editor"
                         x-ref="editor"
                         contenteditable="true"
                         @input="bodyHtml = $el.innerHTML"
                         @paste.prevent="handlePaste($event)"
                         style="min-height:220px;padding:1rem 1.1rem;border:1.5px solid var(--border);border-radius:0 0 8px 8px;font-size:.9rem;color:var(--text-primary);line-height:1.75;outline:none;transition:border-color .15s"
                         onfocus="this.style.borderColor='var(--primary-dark)'"
                         onblur="this.style.borderColor='var(--border)'">
                    </div>

                    {{-- Hidden field synced on submit --}}
                    <input type="hidden" name="body_html" x-model="bodyHtml">

                    <p style="font-size:.75rem;color:var(--text-muted);margin:.45rem 0 0">
                        Use the merge tag chips above to personalise each email. Leave blank to use the default body content.
                    </p>
                </div>

                {{-- ── CTA Button ── --}}
                <div style="background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.5rem">
                    <h3 style="font-size:.9rem;font-weight:700;color:var(--text-primary);margin:0 0 1.1rem;display:flex;align-items:center;gap:.5rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Call-to-Action Button <span style="font-size:.75rem;font-weight:500;color:var(--text-muted)">(optional)</span>
                    </h3>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">
                                Button Label
                            </label>
                            <input type="text" name="cta_text"
                                   value="{{ old('cta_text', $template->cta_text ?? '') }}"
                                   placeholder="e.g. View My Meal Plan"
                                   style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary-dark)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem">
                                Button URL
                            </label>
                            <input type="url" name="cta_url"
                                   value="{{ old('cta_url', $template->cta_url ?? '') }}"
                                   placeholder="https://your-portal.com/..."
                                   style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:8px;font-size:.9rem;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary-dark)'"
                                   onblur="this.style.borderColor='var(--border)'">
                        </div>
                    </div>
                    <p style="font-size:.75rem;color:var(--text-muted);margin:.45rem 0 0">
                        A green button will appear at the bottom of the email body when both fields are filled in.
                    </p>
                </div>

                {{-- ── Actions ── --}}
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                    <div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap">
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.4rem;background:var(--primary-dark);color:#fff;border:none;border-radius:9px;font-size:.88rem;font-weight:700;cursor:pointer;transition:opacity .15s"
                                onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Template
                        </button>

                        <a href="{{ route('email-templates.preview', $type) }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.2rem;border:1.5px solid var(--border);border-radius:9px;font-size:.88rem;font-weight:600;text-decoration:none;color:var(--text-muted);transition:all .15s"
                           onmouseover="this.style.borderColor='var(--primary-dark)';this.style.color='var(--primary-dark)'"
                           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview in New Tab
                        </a>
                    </div>

                    {{-- Send test email --}}
                    <form method="POST" action="{{ route('email-templates.send-test', $type) }}"
                          onsubmit="return confirm('Send a test email to {{ auth()->user()->email }}?')">
                        @csrf
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.2rem;border:1.5px solid #d1d5db;border-radius:9px;font-size:.88rem;font-weight:600;background:#fff;color:#374151;cursor:pointer;transition:all .15s"
                                onmouseover="this.style.borderColor='#6b7280'" onmouseout="this.style.borderColor='#d1d5db'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send Test Email
                        </button>
                    </form>
                </div>

            </div>
        </form>
    </div>

    <script>
    function emailEditor(initialContent) {
        return {
            bodyHtml: initialContent,

            init() {
                // Set initial content into the contenteditable div
                this.$nextTick(() => {
                    if (this.$refs.editor) {
                        this.$refs.editor.innerHTML = this.bodyHtml || '';
                    }
                });
            },

            exec(cmd, value = null) {
                document.execCommand(cmd, false, value);
                this.$refs.editor.focus();
                this.bodyHtml = this.$refs.editor.innerHTML;
            },

            insertTag(tag) {
                this.$refs.editor.focus();
                document.execCommand('insertText', false, '{' + tag + '}');
                this.bodyHtml = this.$refs.editor.innerHTML;
            },

            insertLink() {
                const url = prompt('Enter URL:');
                if (url) {
                    document.execCommand('createLink', false, url);
                    this.bodyHtml = this.$refs.editor.innerHTML;
                }
            },

            handlePaste(e) {
                // Paste as plain text to avoid injecting external styles
                const text = (e.clipboardData || window.clipboardData).getData('text/plain');
                document.execCommand('insertText', false, text);
                this.bodyHtml = this.$refs.editor.innerHTML;
            },

            syncContent() {
                this.bodyHtml = this.$refs.editor.innerHTML;
            },
        };
    }
    </script>
</x-app-layout>
