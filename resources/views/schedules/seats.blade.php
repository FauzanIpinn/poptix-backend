<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pilih Kursi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-bold">{{ $schedule->movie->title }}</h3>
                    <p class="text-gray-600">
                        {{ $schedule->studio->cinema->name }} ({{ $schedule->studio->cinema->brand }}) — {{ $schedule->studio->name }} —
                        {{ $schedule->show_date->format('d M Y') }},
                        {{ \Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i') }}
                    </p>
                    <p class="text-gray-600">Harga: Rp{{ number_format($schedule->price, 0, ',', '.') }} / kursi</p>
                </div>

                <div class="mb-4 flex gap-4 text-sm">
                    <span class="flex items-center gap-1"><span class="w-4 h-4 bg-gray-200 rounded"></span> Tersedia</span>
                    <span class="flex items-center gap-1"><span class="w-4 h-4 bg-red-400 rounded"></span> Sudah Dipesan</span>
                    <span class="flex items-center gap-1"><span class="w-4 h-4 bg-indigo-600 rounded"></span> Dipilih</span>
                </div>

                <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    <div class="mb-6 text-center bg-gray-100 py-2 rounded text-sm text-gray-500">LAYAR</div>

                    @foreach ($seats->groupBy('row') as $row => $seatsInRow)
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 font-bold">{{ $row }}</span>
                            @foreach ($seatsInRow->sortBy('number') as $seat)
                                @php $isBooked = $seat->is_booked; @endphp
                                <label class="cursor-pointer {{ $isBooked ? 'cursor-not-allowed' : '' }}">
                                    <input
                                        type="checkbox"
                                        name="seat_ids[]"
                                        value="{{ $seat->id }}"
                                        class="hidden peer"
                                        {{ $isBooked ? 'disabled' : '' }}
                                    >
                                    <span class="block w-8 h-8 flex items-center justify-center rounded text-xs
                                        {{ $isBooked ? 'bg-red-400 text-white' : 'bg-gray-200 peer-checked:bg-indigo-600 peer-checked:text-white' }}">
                                        {{ $seat->number }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                            Pesan Kursi Terpilih
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>