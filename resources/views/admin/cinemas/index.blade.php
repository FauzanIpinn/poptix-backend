@extends('layouts.admin')

@section('header', 'Penjadwalan Bioskop')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-white">Manajemen Jadwal Tayang</h2>
        <p class="text-ticketor-gray text-sm mt-1">Atur penayangan film pada studio XXI, CGV, dan Cinepolis.</p>
    </div>
    <a href="{{ route('admin.schedules.create') }}" class="bg-ticketor-neon text-black px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-yellow-400 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Jadwal
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
                    <th class="py-4 px-6 font-medium">Film</th>
                    <th class="py-4 px-6 font-medium">Studio / Cinema</th>
                    <th class="py-4 px-6 font-medium">Waktu Tayang</th>
                    <th class="py-4 px-6 font-medium">Harga Tiket</th>
                    <th class="py-4 px-6 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-white">
                @forelse($schedules as $schedule)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <td class="py-4 px-6 font-semibold">{{ $schedule->movie->title }}</td>
                    <td class="py-4 px-6">
                        <span class="bg-gray-800 text-ticketor-neon border border-gray-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                            {{ $schedule->studio->name ?? '-' }} · {{ $schedule->cinema->name }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y - H:i') }} WIB
                    </td>
                    <td class="py-4 px-6 font-medium text-ticketor-neon">
                        Rp {{ number_format($schedule->price, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-ticketor-gray">
                        Belum ada jadwal tayang yang dikonfigurasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection