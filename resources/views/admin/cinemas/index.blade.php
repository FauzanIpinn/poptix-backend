@extends('layouts.admin')

@section('header', 'Manajemen Bioskop')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-white">Daftar Bioskop</h2>
        <p class="text-ticketor-gray text-sm mt-1">Kelola data bioskop rekanan (XXI, CGV, Cinepolis).</p>
    </div>
    <a href="{{ route('admin.cinemas.create') }}" class="bg-ticketor-neon text-black px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-yellow-400 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Bioskop
    </a>
</div>

@if(session('success'))
<div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="bg-ticketor-card rounded-xl border border-gray-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/30 text-ticketor-gray border-b border-gray-800">
                    <th class="py-4 px-6 font-medium">Nama Bioskop</th>
                    <th class="py-4 px-6 font-medium">Brand</th>
                    <th class="py-4 px-6 font-medium">Kota</th>
                    <th class="py-4 px-6 font-medium">Alamat</th>
                    <th class="py-4 px-6 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-white">
                @forelse($cinemas as $cinema)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <td class="py-4 px-6 font-semibold">{{ $cinema->name }}</td>
                    <td class="py-4 px-6">
                        <span class="bg-gray-800 text-ticketor-neon border border-gray-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                            {{ $cinema->brand }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-ticketor-gray">{{ $cinema->city }}</td>
                    <td class="py-4 px-6 text-ticketor-gray text-xs max-w-xs truncate">{{ $cinema->address }}</td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="{{ route('admin.cinemas.edit', $cinema) }}" class="text-ticketor-neon hover:text-yellow-400 transition">Edit</a>
                        <form action="{{ route('admin.cinemas.destroy', $cinema) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus bioskop ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-ticketor-gray">
                        Belum ada data bioskop yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($cinemas->hasPages())
    <div class="p-4 border-t border-gray-800">
        {{ $cinemas->links() }}
    </div>
    @endif
</div>
@endsection
