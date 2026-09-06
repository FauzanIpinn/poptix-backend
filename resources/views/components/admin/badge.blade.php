@props([
    'variant' => 'default', // 'neon', 'success', 'warning', 'danger', 'info', 'brand-xxi', 'brand-cgv', 'brand-cinepolis', 'default'
    'size' => 'md', // 'sm', 'md'
])

@php
    $baseClasses = 'inline-flex items-center font-semibold rounded-md border tracking-wide uppercase font-mono';
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        default => 'px-2.5 py-1 text-xs',
    };

    $variantClasses = match($variant) {
        'neon', 'success' => 'bg-ticketor-neon/10 text-ticketor-neon border-ticketor-neon/30',
        'warning' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
        'danger' => 'bg-red-500/10 text-red-400 border-red-500/30',
        'info' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
        'brand-xxi' => 'bg-amber-500/10 text-amber-300 border-amber-500/40 font-bold',
        'brand-cgv' => 'bg-red-600/10 text-red-400 border-red-600/40 font-bold',
        'brand-cinepolis' => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/40 font-bold',
        default => 'bg-gray-800 text-ticketor-gray border-gray-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
    {{ $slot }}
</span>
