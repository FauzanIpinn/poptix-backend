@props([
    'variant' => 'primary', // 'primary', 'secondary', 'danger', 'outline', 'ghost'
    'size' => 'md',        // 'sm', 'md', 'lg'
    'type' => 'button',
    'as' => 'button',      // 'button' or 'a'
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-ticketor-dark disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer gap-2';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-ticketor-neon text-black hover:bg-yellow-300 focus:ring-ticketor-neon shadow-lg shadow-ticketor-neon/20',
        'secondary' => 'bg-gray-800 text-white hover:bg-gray-700 border border-gray-700 focus:ring-gray-600',
        'danger' => 'bg-red-600/90 text-white hover:bg-red-700 focus:ring-red-500 shadow-md shadow-red-900/30',
        'outline' => 'bg-transparent text-ticketor-gray hover:text-white border border-gray-700 hover:border-gray-500 focus:ring-gray-500',
        'ghost' => 'bg-transparent text-ticketor-gray hover:text-white hover:bg-gray-800/60',
        default => 'bg-ticketor-neon text-black hover:bg-yellow-300 focus:ring-ticketor-neon',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($as === 'a' || $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
