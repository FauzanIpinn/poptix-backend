@extends('layouts.admin')

@section('header', 'Edit Film')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Edit Data Film</h2>
            <p class="text-ticketor-gray text-sm mt-1">Perbarui informasi film {{ $movie->title }}</p>
        </div>
        <a href="{{ route('admin.movies.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Batal
        </a>
    </div>

    <form action="{{ route('admin.movies.update', $movie) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')
        
        <div class="lg:col-span-2 bg-ticketor-card p-6 rounded-xl border border-gray-800 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-ticketor-gray mb-2">Judul Film</label>
                <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="genre" class="block text-sm font-medium text-ticketor-gray mb-2">Genre</label>
                    <input type="text" name="genre" id="genre" value="{{ old('genre', $movie->genre) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                    @error('genre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-ticketor-gray mb-2">Durasi (Menit)</label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration', $movie->duration) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                    @error('duration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-ticketor-gray mb-2">Sinopsis Film</label>
                <textarea name="description" id="description" rows="5" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">{{ old('description', $movie->description) }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800">
                <label class="block text-sm font-medium text-ticketor-gray mb-3">Poster Saat Ini</label>
                
                @if($movie->poster)
                    <div class="mb-4 rounded-lg overflow-hidden border border-gray-800">
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="w-full h-64 object-cover">
                    </div>
                @endif

                <label class="block text-sm font-medium text-ticketor-gray mb-2">Ganti Poster (Opsional)</label>
                <input type="file" name="poster" id="poster" class="w-full text-xs text-ticketor-gray file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-ticketor-neon file:text-black hover:file:bg-yellow-400">
                @error('poster') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-ticketor-neon text-black font-bold py-3.5 rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-ticketor-neon/10">
                Perbarui Film
            </button>
        </div>
    </form>
</div>
@endsection