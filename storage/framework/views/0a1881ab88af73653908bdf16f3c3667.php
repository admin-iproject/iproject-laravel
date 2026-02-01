<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'slideout',
    'side' => 'right', // 'left' or 'right'
    'width' => 'md', // 'sm', 'md', 'lg', 'xl', 'full'
    'title' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'slideout',
    'side' => 'right', // 'left' or 'right'
    'width' => 'md', // 'sm', 'md', 'lg', 'xl', 'full'
    'title' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
?>

<div 
    x-data="{ open: false }"
    @slideout-<?php echo e($id); ?>.window="open = true"
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
            <div class="pointer-events-none fixed inset-y-0 <?php echo e($sideClasses); ?> flex <?php echo e($widthClass); ?> w-full">
                <div 
                    x-show="open"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="<?php echo e($translateHidden); ?>"
                    x-transition:enter-end="<?php echo e($translateVisible); ?>"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="<?php echo e($translateVisible); ?>"
                    x-transition:leave-end="<?php echo e($translateHidden); ?>"
                    class="pointer-events-auto w-full"
                >
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <!-- Header -->
                        <div class="bg-primary-600 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-white">
                                    <?php echo e($title); ?>

                                </h2>
                                <button 
                                    @click="open = false" 
                                    type="button" 
                                    class="rounded-md text-primary-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
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
                            <?php echo e($slot); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/components/slideout.blade.php ENDPATH**/ ?>