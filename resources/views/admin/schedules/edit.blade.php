@extends('layouts.admin')

@section('header', 'Edit Jadwal Tayang')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Edit Jadwal Tayang</h2>
            <p class="text-ticketor-gray text-sm mt-1">Perbarui informasi jadwal film {{ $schedule->movie->title ?? '' }}.</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Batal
        </a>
    </div>

    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="bg-ticketor-card p-6 rounded-xl border border-gray-800 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="movie_id" class="block text-sm font-medium text-ticketor-gray mb-2">Pilih Film</label>
            <select name="movie_id" id="movie_id" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ old('movie_id', $schedule->movie_id) == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }} ({{ $movie->duration }} Menit)
                    </option>
                @endforeach
            </select>
            @error('movie_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="studio_id" class="block text-sm font-medium text-ticketor-gray mb-2">Pilih Studio</label>
            <select name="studio_id" id="studio_id" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @foreach($studios as $studio)
                    <option value="{{ $studio->id }}" {{ old('studio_id', $schedule->studio_id) == $studio->id ? 'selected' : '' }}>
                        {{ $studio->cinema->name ?? 'Bioskop' }} - {{ $studio->name }}
                    </option>
                @endforeach
            </select>
            @error('studio_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="show_date" class="block text-sm font-medium text-ticketor-gray mb-2">Tanggal Tayang</label>
                <input type="date" name="show_date" id="show_date" value="{{ old('show_date', $schedule->show_date?->format('Y-m-d')) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('show_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="show_time" class="block text-sm font-medium text-ticketor-gray mb-2">Jam Tayang (WIB)</label>
                <input type="time" name="show_time" id="show_time" value="{{ old('show_time', \Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i')) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                @error('show_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-ticketor-gray mb-2">Harga Tiket (Rp)</label>
            <input type="number" name="price" id="price" value="{{ old('price', $schedule->price) }}" required min="0" step="1000" class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
            @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-ticketor-neon text-black font-bold py-3.5 rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-ticketor-neon/10 mt-4">
            Perbarui Jadwal
        </button>
    </form>
</div>
@endsection
