<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Film</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($movie->poster)
                    <img src="{{ $movie->poster }}" class="w-24 h-36 object-cover rounded mb-4">
                @endif

                <form action="{{ route('admin.movies.update', $movie) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Judul</label>
                        <input type="text" name="title" value="{{ old('title', $movie->title) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Ganti Poster (opsional)</label>
                        <input type="file" name="poster" class="mt-1 block w-full">
                        @error('poster') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Sinopsis</label>
                        <textarea name="synopsis" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('synopsis', $movie->synopsis) }}</textarea>
                        @error('synopsis') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Genre</label>
                            <input type="text" name="genre" value="{{ old('genre', $movie->genre) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('genre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Durasi (menit)</label>
                            <input type="number" name="duration" value="{{ old('duration', $movie->duration) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('duration') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Rating</label>
                            <input type="text" name="rating" value="{{ old('rating', $movie->rating) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('rating') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Trailer (URL)</label>
                            <input type="text" name="trailer" value="{{ old('trailer', $movie->trailer) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('trailer') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="coming_soon" {{ old('status', $movie->status) === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                            <option value="now_showing" {{ old('status', $movie->status) === 'now_showing' ? 'selected' : '' }}>Now Showing</option>
                        </select>
                        @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                        <a href="{{ route('admin.movies.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>