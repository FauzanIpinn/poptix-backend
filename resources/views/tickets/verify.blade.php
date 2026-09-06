<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tiket - POPTIX Gate Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 font-sans">

    <!-- Header Terminal -->
    <div class="max-w-lg w-full text-center mb-6">
        <div class="inline-flex items-center gap-2 bg-slate-800 border border-slate-700 px-4 py-1.5 rounded-full text-xs font-mono text-slate-400 mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            POPTIX GATE SCANNER TERMINAL
        </div>
        <h1 class="text-xl font-bold tracking-tight text-white">Hasil Verifikasi Scan E-Ticket</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Kode: {{ $bookingCode }}</p>
    </div>

    <!-- Alert Notifikasi Flash -->
    @if(session('success'))
        <div class="max-w-lg w-full mb-4 bg-emerald-500/20 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-lg w-full mb-4 bg-amber-500/20 border border-amber-500 text-amber-300 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-lg w-full mb-4 bg-rose-500/20 border border-rose-500 text-rose-300 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Container Hasil Verifikasi -->
    <div class="max-w-lg w-full bg-slate-800 rounded-2xl border border-slate-700 shadow-2xl overflow-hidden">
        
        @if ($booking && $booking->status === 'paid')
            <!-- STATUS TIKET: VALID / PAID -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white text-center">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="bg-white text-emerald-800 text-xs font-extrabold uppercase px-3 py-1 rounded-full tracking-wider">
                    TIKET VALID & RESMI
                </span>
                <h2 class="text-2xl font-black mt-2">{{ $booking->schedule->movie->title }}</h2>
                <p class="text-xs text-emerald-100 mt-1">
                    {{ $booking->schedule->cinema->name }} &bull; {{ $booking->schedule->studio->name ?? 'Studio 1' }}
                </p>
            </div>

            <!-- Detail Tiket -->
            <div class="p-6 space-y-4 text-sm text-slate-300">
                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-700">
                    <div>
                        <span class="text-xs text-slate-400 font-mono uppercase block">Jadwal Tayang</span>
                        <p class="font-bold text-white text-base">
                            {{ \Illuminate\Support\Carbon::parse($booking->schedule->show_time)->format('H:i') }} WIB
                        </p>
                        <p class="text-xs text-slate-400">{{ $booking->schedule->show_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-mono uppercase block">Nama Pemesan</span>
                        <p class="font-bold text-white">{{ $booking->user->name ?? 'Tamu' }}</p>
                        <p class="text-xs text-slate-400 font-mono">{{ $booking->user->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Kursi -->
                <div>
                    <span class="text-xs text-slate-400 font-mono uppercase block mb-2">Kursi Diizinkan Masuk ({{ $booking->bookingSeats->count() }} Kursi)</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($booking->bookingSeats as $bs)
                            <span class="bg-slate-900 text-yellow-400 border border-slate-700 px-3.5 py-1.5 rounded-lg font-mono font-black text-lg">
                                {{ $bs->seat->code }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Status Check-in -->
                <div class="pt-4 border-t border-slate-700">
                    <span class="text-xs text-slate-400 font-mono uppercase block mb-2">Status Pintu Masuk (Gate)</span>
                    @if ($booking->checked_in_at)
                        <div class="bg-slate-900 p-4 rounded-xl border border-slate-700 flex items-center justify-between">
                            <div class="flex items-center gap-3 text-emerald-400 text-xs font-semibold">
                                <span class="w-3 h-3 bg-emerald-500 rounded-full flex-shrink-0"></span>
                                <div>
                                    <p class="font-bold text-sm text-white">SUDAH CHECK-IN</p>
                                    <p class="text-slate-400 text-[11px] font-mono">Waktu: {{ $booking->checked_in_at->format('d M Y, H:i:s') }} WIB</p>
                                </div>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-300 text-xs px-2.5 py-1 rounded font-mono">TERPAKAI</span>
                        </div>
                    @else
                        <div class="bg-slate-900 p-4 rounded-xl border border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2 text-cyan-400 text-xs font-semibold">
                                    <span class="w-2.5 h-2.5 bg-cyan-400 rounded-full animate-ping"></span>
                                    <span>Menunggu Check-in Petugas</span>
                                </div>
                                <span class="bg-cyan-500/20 text-cyan-300 text-xs px-2 py-0.5 rounded font-mono">BELUM DIGUNAKAN</span>
                            </div>
                            
                            <form action="{{ route('tickets.checkin', $booking->booking_code) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-black text-sm uppercase py-3 rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Izinkan Masuk / Check-in Sekarang
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        @elseif ($booking)
            <!-- STATUS TIKET: BELUM LUNAS / EXPIRED / CANCELLED -->
            <div class="bg-gradient-to-r from-amber-600 to-rose-700 p-6 text-white text-center">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="bg-white text-rose-800 text-xs font-extrabold uppercase px-3 py-1 rounded-full tracking-wider">
                    TIKET BELUM LUNAS / TIDAK BERLAKU
                </span>
                <h2 class="text-2xl font-black mt-2">Akses Ditolak</h2>
                <p class="text-xs text-rose-100 mt-1">Status Pemesanan: <strong class="uppercase">{{ $booking->status }}</strong></p>
            </div>

            <div class="p-6 space-y-3 text-sm text-slate-300">
                <p class="text-slate-400">Pemesanan ini tidak dapat digunakan untuk masuk studio karena belum diselesaikan pembayarannya atau telah kadaluarsa.</p>
                <div class="bg-slate-900 p-4 rounded-xl text-xs space-y-1 font-mono">
                    <p>Kode: {{ $booking->booking_code }}</p>
                    <p>Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    <p>Status: {{ strtoupper($booking->status) }}</p>
                </div>
            </div>

        @else
            <!-- STATUS TIKET: TIDAK DITEMUKAN / PALSU -->
            <div class="bg-gradient-to-r from-rose-700 to-red-900 p-6 text-white text-center">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="bg-white text-rose-900 text-xs font-extrabold uppercase px-3 py-1 rounded-full tracking-wider">
                    TIKET TIDAK DITEMUKAN / PALSU
                </span>
                <h2 class="text-2xl font-black mt-2">Kode Tidak Terdaftar</h2>
            </div>

            <div class="p-6 text-center text-slate-300 space-y-2">
                <p class="text-sm">Kode tiket <strong class="font-mono text-white">{{ $bookingCode }}</strong> tidak tercatat di database sistem POPTIX.</p>
                <p class="text-xs text-slate-500">Pastikan pengunjung menunjukkan QR Code resmi dari aplikasi Poptix.</p>
            </div>
        @endif

        <!-- Footer Terminal -->
        <div class="bg-slate-900 p-4 border-t border-slate-700 flex justify-between items-center text-xs text-slate-400 font-mono">
            <span>POPTIX SECURITY SYSTEM</span>
            <a href="{{ url('/') }}" class="text-cyan-400 hover:underline">Halaman Utama &rarr;</a>
        </div>
    </div>

</body>
</html>
