@extends('layouts.admin')

@section('header', 'Tambah Jadwal Tayang')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Atur Jadwal Tayang</h2>
            <p class="text-ticketor-gray text-sm mt-1">Sistem akan memvalidasi bentrokan waktu secara otomatis.</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Batal
        </a>
    </div>

    <form action="{{ route('admin.schedules.store') }}" method="POST" class="bg-ticketor-card p-6 rounded-xl border border-gray-800 space-y-5">
        @csrf

        <div>
            <label for="movie_id" class="block text-sm font-medium text-ticketor-gray mb-2">Pilih Film</label>
            <select name="movie_id" id="movie_id" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                <option value="">-- Pilih Film --</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration }} Menit)
                    </option>
                @endforeach
            </select>
            @error('movie_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="cinema_id" class="block text-sm font-medium text-ticketor-gray mb-2">Pilih Studio / Bioskop</label>
            <select name="cinema_id" id="cinema_id" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                <option value="">-- Pilih Cinema --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}
                    </option>
                @endforeach
            </select>
            @error('cinema_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-ticketor-gray mb-2">Waktu Tayang</label>
                <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('start_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-ticketor-gray mb-2">Harga Tiket (Rp)</label>
                <input type="number" name="price" id="price" value="{{ old('price', 50000) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="w-full bg-ticketor-neon text-black font-bold py-3.5 rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-ticketor-neon/10 mt-4">
            Publikasikan Jadwal
        </button>
    </form>
</div>
@endsection