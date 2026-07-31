<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Booking</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Booking</p>
                        <p class="text-xl font-bold">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="px-3 py-1 rounded text-sm
                        {{ $booking->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $booking->status === 'cancelled' || $booking->status === 'expired' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <p><span class="text-gray-500">Film:</span> {{ $booking->schedule->movie->title }}</p>
                    <p><span class="text-gray-500">Bioskop:</span> {{ $booking->schedule->cinema->name }} ({{ $booking->schedule->cinema->brand }})</p>
                    <p><span class="text-gray-500">Jadwal:</span> {{ $booking->schedule->show_date->format('d M Y') }}, {{ \Illuminate\Support\Carbon::parse($booking->schedule->show_time)->format('H:i') }}</p>
                    <p><span class="text-gray-500">Kursi:</span> {{ $booking->bookingSeats->map(fn($bs) => $bs->seat->code)->join(', ') }}</p>
                    <p><span class="text-gray-500">Total Harga:</span> Rp{{ number_format($booking->total_price, 0, ',', '.') }}</p>

                    @if ($booking->status === 'pending' && $booking->expires_at)
                        <p class="text-red-600 text-sm">Selesaikan pembayaran sebelum {{ $booking->expires_at->format('H:i') }}</p>
                    @endif
                </div>
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('bookings.index') }}" class="text-indigo-600 underline">Lihat Semua Booking Saya</a>                
                    @can('cancel', $booking)
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin batalkan booking ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-red-600 underline">Batalkan Booking</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>