<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booking Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @forelse ($bookings as $booking)
                    <a href="{{ route('bookings.show', $booking) }}" class="block border-b py-4 hover:bg-gray-50">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-bold">{{ $booking->schedule->movie->title }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->schedule->cinema->name }} —
                                    {{ $booking->schedule->show_date->format('d M Y') }}
                                </p>
                            </div>
                            <span class="text-sm px-2 py-1 rounded h-fit
                                {{ $booking->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-center py-8">Belum ada riwayat booking.</p>
                @endforelse

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>