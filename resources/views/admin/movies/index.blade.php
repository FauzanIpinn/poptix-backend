@extends('layouts.admin')

@section('header', 'Manajemen Film')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-white">Katalog Film</h2>
        <p class="text-ticketor-gray text-sm mt-1">Kelola data film yang akan tayang di seluruh bioskop.</p>
    </div>
    <a href="{{ route('admin.movies.create') }}" class="bg-ticketor-neon text-black px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-yellow-400 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Film
    </a>
</div>

<!-- Menampilkan Pesan Sukses -->
@if(session('success'))
<div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="bg-ticketor-card rounded-xl border border-gray-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/30 text-ticketor-gray border-b border-gray-800">
                    <th class="py-4 px-6 font-medium">Poster</th>
                    <th class="py-4 px-6 font-medium">Judul Film</th>
                    <th class="py-4 px-6 font-medium">Durasi</th>
                    <th class="py-4 px-6 font-medium">Genre</th>
                    <th class="py-4 px-6 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-white">
                @forelse($movies as $movie)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <td class="py-4 px-6">
                        @if($movie->poster)
                            <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="w-12 h-16 object-cover rounded-md border border-gray-700">
                        @else
                            <div class="w-12 h-16 bg-gray-800 rounded-md border border-gray-700 flex items-center justify-center text-xs text-gray-500">No Image</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 font-semibold">{{ $movie->title }}</td>
                    <td class="py-4 px-6">{{ $movie->duration }} Menit</td>
                    <td class="py-4 px-6">
                        <span class="bg-gray-800 text-ticketor-gray px-2 py-1 rounded text-xs">{{ $movie->genre }}</span>
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('admin.movies.edit', $movie) }}" class="text-ticketor-neon hover:text-yellow-400 transition">Edit</a>
                        <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus film ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-ticketor-gray">
                        Belum ada data film. Silakan tambah film baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($movies->hasPages())
    <div class="p-4 border-t border-gray-800">
        {{ $movies->links() }}
    </div>
    @endif
</div>
@endsection