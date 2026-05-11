<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl" style="color:var(--text-primary)">
                Email Template Preview
            </h2>
            <p class="text-sm" style="color:var(--text-muted)">
                Browse and preview all system email templates with realistic dummy data.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($templates as $tpl)
                    @php
                        $params = array_merge(['template' => $tpl['key']], $tpl['params'] ?? []);
                        $url    = route('email-preview.show', $params);
                    @endphp
                    <div class="dash-card flex flex-col gap-3">
                        <div class="text-3xl">{{ $tpl['icon'] }}</div>
                        <div>
                            <h3 class="font-semibold text-base" style="color:var(--text-primary)">
                                {{ $tpl['label'] }}
                            </h3>
                            <p class="text-sm mt-1" style="color:var(--text-muted)">
                                {{ $tpl['description'] }}
                            </p>
                        </div>
                        <div class="mt-auto pt-2">
                            <a href="{{ $url }}"
                               target="_blank"
                               rel="noopener"
                               class="mp-btn mp-btn-primary inline-flex items-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
