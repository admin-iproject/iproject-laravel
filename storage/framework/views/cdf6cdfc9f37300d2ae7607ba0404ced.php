<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'tabs' => [],
    'side' => 'right'
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
    'tabs' => [],
    'side' => 'right'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$sideClass = $side === 'left' ? 'left-0' : 'right-0';
?>

<div class="fixed <?php echo e($sideClass); ?> top-1/3 z-40 flex flex-col gap-2">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button
            type="button"
            x-data
            @click.prevent="window.dispatchEvent(new CustomEvent('slideout-<?php echo e($tab['id']); ?>'))"
            class="group relative bg-blue-400 hover:bg-blue-200 text-black shadow-lg transition-all hover:<?php echo e($side === 'left' ? 'pl' : 'pr'); ?>-6 cursor-pointer <?php echo e($side === 'left' ? 'rounded-r-lg' : 'rounded-l-lg'); ?>"
            style="writing-mode: vertical-rl; text-orientation: mixed; padding: 12px 8px;"
            title="<?php echo e($tab['label']); ?>"
        >
            <span class="text-sm font-semibold tracking-wider flex items-center gap-2">
                <?php echo e(strtoupper($tab['label'])); ?>

                <?php if(isset($tab['count']) && $tab['count'] > 0): ?>
                    <span class="bg-white text-blue-400 rounded-full px-2 py-1 text-xs font-bold">
                        <?php echo e($tab['count']); ?>

                    </span>
                <?php endif; ?>
            </span>
        </button>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/components/edge-tabs.blade.php ENDPATH**/ ?>