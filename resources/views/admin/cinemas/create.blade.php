<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Bioskop</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.cinemas.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Nama Cabang</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="cth: XXI Malang Town Square" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Brand</label>
                        <select name="brand" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- Pilih Brand --</option>
                            <option value="XXI" {{ old('brand') === 'XXI' ? 'selected' : '' }}>XXI</option>
                            <option value="CGV" {{ old('brand') === 'CGV' ? 'selected' : '' }}>CGV</option>
                            <option value="Cinepolis" {{ old('brand') === 'Cinepolis' ? 'selected' : '' }}>Cinepolis</option>
                        </select>
                        @error('brand') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Kota</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
                        <a href="{{ route('admin.cinemas.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>