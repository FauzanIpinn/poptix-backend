<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Film</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
                @endif

                <a href="{{ route('admin.movies.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-black rounded">
                    + Tambah Film
                </a>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="p-2">Poster</th>
                            <th class="p-2">Judul</th>
                            <th class="p-2">Genre</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movies as $movie)
                        <tr class="border-b">
                            <td class="p-2">
                                @if ($movie->poster)
                                <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-16 h-24 object-cover rounded">
                                @else
                                <span class="text-gray-400 text-sm">Tidak ada poster</span>
                                @endif
                            </td>
                            <td class="p-2">{{ $movie->title }}</td>
                            <td class="p-2">{{ $movie->genre }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 text-xs rounded {{ $movie->status === 'now_showing' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $movie->status === 'now_showing' ? 'Now Showing' : 'Coming Soon' }}
                                </span>
                            </td>
                            <td class="p-2 space-x-2">
                                <a href="{{ route('admin.movies.edit', $movie) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus film ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-400">Belum ada data film.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $movies->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>