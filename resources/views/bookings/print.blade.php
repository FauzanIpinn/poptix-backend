<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $booking->booking_code }} - POPTIX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
                color: black;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: 2px solid #000;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Tombol Aksi di Atas -->
    <div class="max-w-md w-full mb-4 flex justify-between items-center no-print">
        <a href="{{ route('bookings.show', $booking) }}" class="text-sm text-gray-600 hover:text-black font-semibold flex items-center gap-1">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase px-4 py-2 rounded-lg shadow transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print / Simpan PDF
        </button>
    </div>

    <!-- Kartu Tiket Cetak -->
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-300 print-card">
        
        <!-- Header Brand -->
        <div class="bg-black text-white p-6 border-b-2 border-dashed border-gray-400 text-center">
            <h2 class="text-xs uppercase tracking-widest text-yellow-400 font-extrabold mb-1">
                {{ $booking->schedule->cinema->brand }} CINEMA PASS
            </h2>
            <h1 class="text-2xl font-black">{{ $booking->schedule->movie->title }}</h1>
            <p class="text-xs text-gray-400 mt-1">
                {{ $booking->schedule->cinema->name }} &bull; {{ $booking->schedule->studio->name ?? 'Studio 1' }}
            </p>
        </div>

        <!-- Body Detail -->
        <div class="p-6 space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                <div>
                    <span class="text-xs text-gray-500 font-semibold uppercase">Tanggal</span>
                    <p class="font-bold text-gray-900">{{ $booking->schedule->show_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->schedule->show_date->translatedFormat('l') }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold uppercase">Jam Tayang</span>
                    <p class="font-extrabold text-indigo-700 text-base">
                        {{ \Illuminate\Support\Carbon::parse($booking->schedule->show_time)->format('H:i') }} WIB
                    </p>
                </div>
            </div>

            <!-- Kursi -->
            <div class="pb-4 border-b border-gray-200">
                <span class="text-xs text-gray-500 font-semibold uppercase block mb-1.5">Nomor Kursi</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($booking->bookingSeats as $bs)
                        <span class="bg-gray-900 text-yellow-400 px-3 py-1.5 rounded-lg font-mono font-bold text-base">
                            {{ $bs->seat->code }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Info Pembayaran -->
            <div class="flex justify-between items-center text-xs pb-4 border-b border-gray-200">
                <div>
                    <p class="text-gray-500">Pemesan: <strong class="text-gray-900">{{ $booking->user->name ?? 'Tamu' }}</strong></p>
                    <p class="text-gray-500">Metode: <strong class="uppercase text-gray-900">{{ $booking->payment_type ?? 'Midtrans' }}</strong></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Total Biaya</p>
                    <p class="font-black text-sm text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="pt-2 text-center">
                <img 
                    src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode(route('tickets.verify', $booking->booking_code)) }}&margin=4" 
                    alt="QR Code Tiket"
                    class="w-36 h-36 mx-auto mb-2 border border-gray-200 rounded-lg p-1 bg-white"
                />
                <p class="font-mono font-black text-lg text-gray-900 tracking-widest">{{ $booking->booking_code }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">Scan QR ini di scanner gerbang bioskop.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 p-3 text-center text-[10px] text-gray-500 uppercase font-mono border-t border-gray-200">
            POPTIX TICKET SYSTEM &bull; GENERATED {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
