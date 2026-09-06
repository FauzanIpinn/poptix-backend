@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-ticketor-card rounded-xl border border-gray-800 shadow-xl overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div>
