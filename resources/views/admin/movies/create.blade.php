@extends('layouts.admin')

@section('header', 'Tambah Film Baru')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Input Master Film</h2>
            <p class="text-ticketor-gray text-sm mt-1">Tambahkan data film baru ke katalog sistem POPTIX.</p>
        </div>
        <a href="{{ route('admin.movies.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <!-- Kolom Kiri: Detail Informasi Film -->
        <div class="lg:col-span-2 bg-ticketor-card p-6 rounded-xl border border-gray-800 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-ticketor-gray mb-2">Judul Film</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Fast & Furious 10" class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="genre" class="block text-sm font-medium text-ticketor-gray mb-2">Genre</label>
                    <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required placeholder="Action, Sci-Fi" class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                    @error('genre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-ticketor-gray mb-2">Durasi (Menit)</label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration') }}" required placeholder="120" class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                    @error('duration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-ticketor-gray mb-2">Sinopsis Film</label>
                <textarea name="description" id="description" rows="5" required placeholder="Tuliskan ringkasan alur cerita film..." class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Kolom Kanan: Upload Poster & Submit -->
        <div class="space-y-6">
            <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800">
                <label class="block text-sm font-medium text-ticketor-gray mb-3">Poster Film</label>
                
                <div class="border-2 border-dashed border-gray-800 hover:border-ticketor-neon rounded-xl p-4 text-center transition flex flex-col items-center justify-center min-h-[260px] bg-ticketor-dark">
                    <svg class="w-10 h-10 text-ticketor-gray mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-xs text-ticketor-gray mb-3">Format JPG, PNG (Maks. 2MB)</p>
                    <input type="file" name="poster" id="poster" required class="text-xs text-ticketor-gray file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-ticketor-neon file:text-black hover:file:bg-yellow-400">
                </div>
                @error('poster') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-ticketor-neon text-black font-bold py-3.5 rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-ticketor-neon/10">
                Simpan Film
            </button>
        </div>
    </form>
</div>
@endsection