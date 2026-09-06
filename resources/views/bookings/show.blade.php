<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $booking->status === 'paid' ? 'E-Ticket Bioskop' : 'Detail Pemesanan Tiket' }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-500/10 border border-green-500 text-green-700 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-500/10 border border-red-500 text-red-700 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($booking->status === 'paid')
                {{-- ════════════════════════════════════════════════════════════════ --}}
                {{-- TAMPILAN E-TICKET RESMI (STATUS: PAID) --}}
                {{-- ════════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">
                    <!-- Header Kartu Tiket Bioskop -->
                    <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-black text-white p-6 sm:p-8 relative">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="inline-flex items-center gap-2 bg-yellow-400 text-black font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                                    <span>{{ $booking->schedule->cinema->brand }} PASSPORT</span>
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $booking->schedule->movie->title }}</h1>
                                <p class="text-gray-400 text-sm mt-1">
                                    {{ $booking->schedule->movie->genre }} &bull; {{ $booking->schedule->movie->duration }} Menit &bull; 
                                    <span class="border border-gray-600 px-1.5 py-0.5 rounded text-xs">{{ $booking->schedule->movie->rating ?? 'SU' }}</span>
                                </p>
                            </div>
                            <span class="bg-green-500 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-lg shadow-green-500/30">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                LUNAS
                            </span>
                        </div>
                    </div>

                    <!-- Body Tiket: Detail Bioskop & Kursi -->
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-6 border-b border-gray-100">
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Bioskop</span>
                                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $booking->schedule->cinema->name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->schedule->cinema->city }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Studio</span>
                                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $booking->schedule->studio->name ?? 'Studio 1' }}</p>
                                <p class="text-xs text-indigo-600 font-medium">Dolby Atmos</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Tanggal</span>
                                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $booking->schedule->show_date->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->schedule->show_date->translatedFormat('l') }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Waktu</span>
                                <p class="font-extrabold text-indigo-600 text-base mt-0.5">
                                    {{ \Illuminate\Support\Carbon::parse($booking->schedule->show_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <!-- Kursi yang Dipesan -->
                        <div>
                            <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider block mb-2">Nomor Kursi Terpilih</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->bookingSeats as $bs)
                                    <div class="bg-gray-900 text-yellow-400 px-4 py-2 rounded-xl font-mono font-black text-lg border border-gray-800 shadow-sm flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $bs->seat->code }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Garis Robekan Tiket (Ticket Notch Effect) -->
                        <div class="relative py-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-dashed border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-between text-xs text-gray-400 uppercase font-mono">
                                <span>POPTIX PASS</span>
                                <span>SCAN DI PINTU MASUK</span>
                            </div>
                        </div>

                        <!-- Bagian QR Code & Scanner Validation Link -->
                        <div class="bg-gray-50 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6 border border-gray-200">
                            <div class="text-center sm:text-left space-y-1">
                                <p class="text-xs font-semibold uppercase text-gray-500 tracking-wider">Kode Booking Resmi</p>
                                <p class="text-2xl font-mono font-black text-gray-900 tracking-widest">{{ $booking->booking_code }}</p>
                                <p class="text-xs text-gray-500">Tunjukkan QR code ini ke petugas bioskop untuk di-scan.</p>
                                @if($booking->checked_in_at)
                                    <div class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-100 font-semibold px-2.5 py-1 rounded-full mt-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Sudah Check-in ({{ $booking->checked_in_at->format('d M Y, H:i') }} WIB)
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-100 font-semibold px-2.5 py-1 rounded-full mt-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Siap Di-scan di Pintu Masuk
                                    </div>
                                @endif
                            </div>

                            <!-- Live QR Code Image -->
                            <div class="bg-white p-3 rounded-2xl shadow-md border border-gray-200 flex-shrink-0 text-center">
                                <img 
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('tickets.verify', $booking->booking_code)) }}&margin=4" 
                                    alt="QR Code Tiket {{ $booking->booking_code }}"
                                    class="w-36 h-36 rounded-lg object-contain mx-auto"
                                />
                                <span class="text-[10px] text-gray-400 font-mono mt-1 block">Live QR Code</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="bg-gray-100 px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('bookings.print', $booking) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak E-Ticket / PDF
                            </a>
                            <a href="{{ route('tickets.verify', $booking->booking_code) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition border border-indigo-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                Simulasi Scan Tiket
                            </a>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                            Lihat Riwayat Pesanan &rarr;
                        </a>
                    </div>
                </div>

            @else
                {{-- ════════════════════════════════════════════════════════════════ --}}
                {{-- TAMPILAN MENUNGGU PEMBAYARAN (PENDING / EXPIRED / CANCELLED) --}}
                {{-- ════════════════════════════════════════════════════════════════ --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 sm:p-8 border border-gray-200">
                    <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-100">
                        <div>
                            <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider">Kode Reservasi</span>
                            <p class="text-2xl font-mono font-black text-gray-900">{{ $booking->booking_code }}</p>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : '' }}
                            {{ $booking->status === 'cancelled' || $booking->status === 'expired' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="space-y-3 text-sm text-gray-700 mb-6">
                        <p><span class="font-medium text-gray-500 w-32 inline-block">Film:</span> <strong class="text-gray-900">{{ $booking->schedule->movie->title }}</strong></p>
                        <p><span class="font-medium text-gray-500 w-32 inline-block">Bioskop:</span> {{ $booking->schedule->cinema->name }} ({{ $booking->schedule->cinema->brand }})</p>
                        <p><span class="font-medium text-gray-500 w-32 inline-block">Jadwal:</span> {{ $booking->schedule->show_date->format('d M Y') }}, {{ \Illuminate\Support\Carbon::parse($booking->schedule->show_time)->format('H:i') }} WIB</p>
                        <p><span class="font-medium text-gray-500 w-32 inline-block">Kursi:</span> <span class="font-bold text-indigo-600 font-mono">{{ $booking->bookingSeats->map(fn($bs) => $bs->seat->code)->join(', ') }}</span></p>
                        <p><span class="font-medium text-gray-500 w-32 inline-block">Total Pembayaran:</span> <strong class="text-lg text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>

                        @if ($booking->status === 'pending' && $booking->expires_at)
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 text-xs flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Selesaikan pembayaran sebelum <strong class="font-bold">{{ $booking->expires_at->format('H:i') }} WIB</strong> (Kursi ditahan 10 menit).</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex flex-wrap gap-3 items-center justify-between">
                        <a href="{{ route('bookings.index') }}" class="text-sm text-gray-600 hover:text-gray-900 transition">
                            &larr; Lihat Semua Booking
                        </a>

                        <div class="flex items-center gap-3">
                            @if ($booking->status === 'pending')
                                <button id="pay-button" data-booking-id="{{ $booking->id }}" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-green-600/20 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Bayar Sekarang (Midtrans)
                                </button>
                            @endif

                            @can('cancel', $booking)
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin batalkan pemesanan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-semibold transition">
                                        Batalkan
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Snap.js dari Midtrans --}}
    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.getElementById('pay-button')?.addEventListener('click', function () {
            const bookingId = this.dataset.bookingId;
            const button = this;

            button.disabled = true;
            button.innerText = 'Memuat Midtrans...';

            fetch(`/bookings/${bookingId}/checkout`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
                .then(response => response.json())
                .then(data => {
                    button.disabled = false;
                    button.innerText = 'Bayar Sekarang (Midtrans)';

                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                window.location.reload();
                            },
                            onPending: function (result) {
                                window.location.reload();
                            },
                            onError: function (result) {
                                alert('Terjadi kesalahan saat memproses pembayaran.');
                            },
                            onClose: function () {
                                console.log('Popup pembayaran ditutup tanpa menyelesaikan transaksi.');
                            }
                        });
                    } else {
                        alert(data.message || 'Gagal memuat halaman pembayaran Midtrans.');
                    }
                })
                .catch(error => {
                    button.disabled = false;
                    button.innerText = 'Bayar Sekarang (Midtrans)';
                    alert('Gagal menghubungi server.');
                });
        });
    </script>
</x-app-layout>
