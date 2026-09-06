@props([
    'disabled' => false,
    'label' => null,
    'name' => '',
    'value' => null,
    'required' => false,
    'hint' => null,
    'rows' => 4,
    'placeholder' => '',
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-wider text-ticketor-gray mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full bg-ticketor-dark/80 border ' . ($errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-800 focus:border-ticketor-neon focus:ring-ticketor-neon') . ' rounded-lg px-4 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:ring-1 transition'
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @if($hint)
        <p class="text-xs text-ticketor-gray mt-1">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-400 mt-1.5 flex items-center gap-1 font-medium">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            {{ $message }}
        </p>
    @enderror
</div>
