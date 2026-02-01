@props([
    'tabs' => [],
    'side' => 'right'
])

@php
$sideClass = $side === 'left' ? 'left-0' : 'right-0';
@endphp

<div class="fixed {{ $sideClass }} top-1/3 z-40 flex flex-col gap-2">
    @foreach($tabs as $tab)
        <button
            type="button"
            x-data
            @click.prevent="window.dispatchEvent(new CustomEvent('slideout-{{ $tab['id'] }}'))"
            class="group relative text-white bg-blue-400 hover:bg-blue-200 hover:text-black shadow-lg transition-all hover:{{ $side === 'left' ? 'pl' : 'pr' }}-6 cursor-pointer {{ $side === 'left' ? 'rounded-r-lg' : 'rounded-l-lg' }}"
            style="writing-mode: vertical-rl; text-orientation: mixed; padding: 12px 8px;"
            title="{{ $tab['label'] }}"
        >
            <span class="text-sm font-semibold tracking-wider flex items-center gap-2">
                {{ strtoupper($tab['label']) }}
                @if(isset($tab['count']) && $tab['count'] > 0)
                    <span class="bg-white text-blue-400 rounded-full px-2 py-1 text-xs font-bold">
                        {{ $tab['count'] }}
                    </span>
                @endif
            </span>
        </button>
    @endforeach
</div>