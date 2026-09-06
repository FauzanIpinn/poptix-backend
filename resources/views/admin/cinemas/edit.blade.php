@extends('layouts.admin')

@section('header', 'Edit Bioskop')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Edit Data Bioskop</h2>
            <p class="text-ticketor-gray text-sm mt-1">Perbarui informasi bioskop {{ $cinema->name }}.</p>
        </div>
        <a href="{{ route('admin.cinemas.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Batal
        </a>
    </div>

    <form action="{{ route('admin.cinemas.update', $cinema) }}" method="POST" class="bg-ticketor-card p-6 rounded-xl border border-gray-800 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-ticketor-gray mb-2">Nama Bioskop</label>
            <input type="text" name="name" id="name" value="{{ old('name', $cinema->name) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="brand" class="block text-sm font-medium text-ticketor-gray mb-2">Brand</label>
            <select name="brand" id="brand" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
                <option value="XXI" {{ old('brand', $cinema->brand) === 'XXI' ? 'selected' : '' }}>XXI</option>
                <option value="CGV" {{ old('brand', $cinema->brand) === 'CGV' ? 'selected' : '' }}>CGV</option>
                <option value="Cinepolis" {{ old('brand', $cinema->brand) === 'Cinepolis' ? 'selected' : '' }}>Cinepolis</option>
            </select>
            @error('brand') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="city" class="block text-sm font-medium text-ticketor-gray mb-2">Kota</label>
            <input type="text" name="city" id="city" value="{{ old('city', $cinema->city) }}" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">
            @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-ticketor-gray mb-2">Alamat Lengkap</label>
            <textarea name="address" id="address" rows="3" required class="w-full bg-ticketor-dark border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-ticketor-neon transition">{{ old('address', $cinema->address) }}</textarea>
            @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-ticketor-neon text-black font-bold py-3.5 rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-ticketor-neon/10 mt-4">
            Perbarui Bioskop
        </button>
    </form>
</div>
@endsection
