@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Stat Card 1 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Tiket Terjual Hari Ini</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($todayTickets) }}</p>
        <span class="text-ticketor-neon text-xs font-semibold mt-2 inline-block">Data harian sistem</span>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Pendapatan (Bulan Ini)</h3>
        <!-- Menggunakan format Rupiah standar -->
        <p class="text-4xl font-bold text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Film Sedang Tayang</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($activeMovies) }}</p>
    </div>
</div>

<!-- Section Tabel Data Terbaru -->
<div class="bg-ticketor-card rounded-xl border border-gray-800 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-white">Transaksi Pemesanan Terakhir</h3>
        <a href="#" class="bg-ticketor-neon text-black px-4 py-2 rounded-md text-sm font-bold hover:bg-yellow-400 transition">Lihat Semua</a>
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
                <!-- Looping dinamis dari $recentBookings -->
                @forelse($recentBookings as $booking)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <!-- str_pad digunakan agar ID terlihat profesional, contoh: #TRX-00012 -->
                    <td class="py-4">#TRX-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-4">{{ $booking->user->name ?? 'User Dihapus' }}</td>
                    <td class="py-4">{{ $booking->schedule->movie->title ?? 'Data Film Tidak Tersedia' }}</td>
                    <td class="py-4">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="py-4">
                        @if($booking->payment_status === 'success')
                            <span class="bg-ticketor-neon/20 text-ticketor-neon px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
                        @elseif($booking->payment_status === 'pending')
                            <span class="bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
                        @else
                            <span class="bg-red-500/20 text-red-500 px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
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
@endsection@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Stat Card 1 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Tiket Terjual Hari Ini</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($todayTickets) }}</p>
        <span class="text-ticketor-neon text-xs font-semibold mt-2 inline-block">Data harian sistem</span>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Total Pendapatan (Bulan Ini)</h3>
        <!-- Menggunakan format Rupiah standar -->
        <p class="text-4xl font-bold text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-ticketor-card p-6 rounded-xl border border-gray-800 hover:border-ticketor-neon transition duration-300">
        <h3 class="text-ticketor-gray text-sm font-medium mb-1">Film Sedang Tayang</h3>
        <p class="text-4xl font-bold text-white">{{ number_format($activeMovies) }}</p>
    </div>
</div>

<!-- Section Tabel Data Terbaru -->
<div class="bg-ticketor-card rounded-xl border border-gray-800 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-white">Transaksi Pemesanan Terakhir</h3>
        <a href="#" class="bg-ticketor-neon text-black px-4 py-2 rounded-md text-sm font-bold hover:bg-yellow-400 transition">Lihat Semua</a>
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
                <!-- Looping dinamis dari $recentBookings -->
                @forelse($recentBookings as $booking)
                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                    <!-- str_pad digunakan agar ID terlihat profesional, contoh: #TRX-00012 -->
                    <td class="py-4">#TRX-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-4">{{ $booking->user->name ?? 'User Dihapus' }}</td>
                    <td class="py-4">{{ $booking->schedule->movie->title ?? 'Data Film Tidak Tersedia' }}</td>
                    <td class="py-4">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="py-4">
                        @if($booking->payment_status === 'success')
                            <span class="bg-ticketor-neon/20 text-ticketor-neon px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
                        @elseif($booking->payment_status === 'pending')
                            <span class="bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
                        @else
                            <span class="bg-red-500/20 text-red-500 px-2 py-1 rounded text-xs font-semibold uppercase">{{ $booking->payment_status }}</span>
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