<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $movie->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex gap-6 mb-6">
                    <div class="w-40 aspect-[2/3] bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if ($movie->poster)
                            <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $movie->title }}</h3>
                        <p class="text-gray-500 text-sm mb-2">
                            {{ $movie->genre }} &bull;
                            {{ floor($movie->duration / 60) }}h {{ $movie->duration % 60 }}m &bull;
                            {{ $movie->rating ?? '-' }}
                        </p>
                        <p class="text-gray-700 text-sm">{{ $movie->synopsis }}</p>

                        @if ($movie->trailer)
                            <a href="{{ $movie->trailer }}" target="_blank" class="inline-block mt-3 text-indigo-600 underline text-sm">
                                Tonton Trailer
                            </a>
                        @endif
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="font-semibold mb-4">Pilih Jadwal Tayang</h4>

                    @forelse ($schedules as $date => $schedulesOnDate)
                        <div class="mb-6">
                            <p class="font-medium text-sm text-gray-600 mb-2">
                                {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                            </p>
                            <div class="space-y-2">
                                @foreach ($schedulesOnDate->groupBy('cinema_id') as $cinemaSchedules)
                                    @php $cinema = $cinemaSchedules->first()->cinema; @endphp
                                    <div class="border rounded-lg p-3">
                                        <p class="text-sm font-medium">{{ $cinema->name }} <span class="text-gray-400">({{ $cinema->brand }})</span></p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach ($cinemaSchedules as $schedule)
                                                <a href="{{ route('schedules.seats', $schedule) }}"
                                                   class="px-3 py-1 border border-indigo-600 text-indigo-600 rounded text-sm hover:bg-indigo-600 hover:text-white">
                                                    {{ \Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Belum ada jadwal tayang untuk film ini.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>