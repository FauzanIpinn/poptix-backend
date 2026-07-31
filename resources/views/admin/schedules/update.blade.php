<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jadwal Film</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Film</label>
                        <select name="movie_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}" {{ old('movie_id', $schedule->movie_id) == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('movie_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Bioskop</label>
                        <select name="cinema_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach ($cinemas as $cinema)
                                <option value="{{ $cinema->id }}" {{ old('cinema_id', $schedule->cinema_id) == $cinema->id ? 'selected' : '' }}>
                                    {{ $cinema->name }} ({{ $cinema->brand }})
                                </option>
                            @endforeach
                        </select>
                        @error('cinema_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Tanggal Tayang</label>
                            <input type="date" name="show_date" value="{{ old('show_date', $schedule->show_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('show_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Jam Tayang</label>
                            <input type="time" name="show_time" value="{{ old('show_time', \Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('show_time') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Harga Tiket (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $schedule->price) }}" min="0" step="1000" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                        <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>