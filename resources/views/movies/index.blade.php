<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Katalog Film</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex gap-2 mb-6">
                    <a href="{{ route('movies.index', ['status' => 'now_showing']) }}"
                       class="px-4 py-2 rounded {{ $status === 'now_showing' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                        Sedang Tayang
                    </a>
                    <a href="{{ route('movies.index', ['status' => 'coming_soon']) }}"
                       class="px-4 py-2 rounded {{ $status === 'coming_soon' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                        Segera Tayang
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @forelse ($movies as $movie)
                        <a href="{{ route('movies.show', $movie) }}" class="block group">
                            <div class="aspect-[2/3] bg-gray-100 rounded-lg overflow-hidden mb-2">
                                @if ($movie->poster)
                                    <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:opacity-80">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                        Tidak ada poster
                                    </div>
                                @endif
                            </div>
                            <p class="font-semibold text-sm">{{ $movie->title }}</p>
                            <p class="text-xs text-gray-500">{{ $movie->genre }}</p>
                        </a>
                    @empty
                        <p class="col-span-4 text-center text-gray-400 py-12">Belum ada film di kategori ini.</p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $movies->appends(['status' => $status])->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>