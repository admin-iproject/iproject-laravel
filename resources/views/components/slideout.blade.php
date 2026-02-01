@props([
    'id' => 'slideout',
    'side' => 'right', // 'left' or 'right'
    'width' => 'md', // 'sm', 'md', 'lg', 'xl', 'full'
    'title' => '',
])

@php
$widthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    'full' => 'max-w-full',
];

$widthClass = $widthClasses[$width] ?? $widthClasses['md'];

$sideClasses = $side === 'left' 
    ? 'left-0' 
    : 'right-0';

$translateHidden = $side === 'left'
    ? '-translate-x-full'
    : 'translate-x-full';

$translateVisible = 'translate-x-0';
@endphp

<div 
    x-data="{ open: false }"
    @slideout-{{ $id }}.window="open = true"
    @keydown.escape.window="open = false"
    x-show="open"
    class="relative z-50"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-gray-500 bg-opacity-75"
    ></div>

    <!-- Slide-out Panel -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 {{ $sideClasses }} flex {{ $widthClass }} w-full">
                <div 
                    x-show="open"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="{{ $translateHidden }}"
                    x-transition:enter-end="{{ $translateVisible }}"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="{{ $translateVisible }}"
                    x-transition:leave-end="{{ $translateHidden }}"
                    class="pointer-events-auto w-full"
                >
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <!-- Header -->
                        <div class="bg-blue-400 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-white">
                                    {{ $title }}
                                </h2>
                                <button 
                                    @click="open = false" 
                                    type="button" 
                                    class="rounded-md text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                >
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="relative flex-1 px-4 py-6 sm:px-6">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>