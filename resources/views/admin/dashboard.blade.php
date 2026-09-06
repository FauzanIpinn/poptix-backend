@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Stat Card 1 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Booking Berhasil Hari Ini</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($todayTickets) }}</p>
        <span class="text-ticketor-neon text-xs font-semibold mt-2 inline-block">Data harian sistem</span>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Pendapatan (Bulan Ini)</h3>
        <p class="text-4xl font-bold text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Master Film</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($activeMovies) }}</p>
    </div>
</div>

<!-- Section Tabel Data Terbaru -->
<div class="bg-ticketor-card rounded-xl border border-gray-800 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-white">Transaksi Pemesanan Terakhir</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-ticketor-gray border-b border-gray-800">
                    <th class="pb-3 font-medium">ID Pesanan</th>
                    <th class="pb-3 font-medium">Pengguna</th>
                    <th class="pb-3 font-medium">Film</th>
                    <th class="pb-3 font-medium">Total (Rp)</th>
                    <th class="pb-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm text-white">
                @forelse($recentBookings as $booking)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <td class="py-4">#{{ $booking->booking_code }}</td>
                    <td class="py-4">{{ $booking->user->name ?? 'User Dihapus' }}</td>
                    <td class="py-4">{{ $booking->schedule->movie->title ?? 'Data Film Tidak Tersedia' }}</td>
                    <td class="py-4">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="py-4">
                        @if($booking->status === 'paid')
                            <span class="bg-ticketor-neon/20 text-ticketor-neon px-2.5 py-1 rounded text-xs font-semibold uppercase">PAID</span>
                        @elseif($booking->status === 'pending')
                            <span class="bg-yellow-500/20 text-yellow-500 px-2.5 py-1 rounded text-xs font-semibold uppercase">PENDING</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="bg-gray-500/20 text-gray-400 px-2.5 py-1 rounded text-xs font-semibold uppercase">CANCELLED</span>
                        @else
                            <span class="bg-red-500/20 text-red-500 px-2.5 py-1 rounded text-xs font-semibold uppercase">{{ strtoupper($booking->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-ticketor-gray">
                        Belum ada transaksi pemesanan saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
