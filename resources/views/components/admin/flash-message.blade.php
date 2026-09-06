@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-ticketor-neon/10 border border-ticketor-neon/30 text-ticketor-neon px-4 py-3.5 rounded-xl flex items-center justify-between shadow-lg shadow-ticketor-neon/5 transition">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    <button @click="show = false" class="text-ticketor-neon/60 hover:text-ticketor-neon transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-lg shadow-red-500/5 transition">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
    <button @click="show = false" class="text-red-400/60 hover:text-red-400 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3.5 rounded-xl shadow-lg shadow-red-500/5">
    <div class="flex items-center gap-2 mb-2 font-bold text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Terdapat beberapa kesalahan input:</span>
    </div>
    <ul class="list-disc list-inside text-xs space-y-1 text-red-300 ml-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
