<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Jadwal Film</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.schedules.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded">
                    + Tambah Jadwal
                </a>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="p-2">Film</th>
                            <th class="p-2">Bioskop</th>
                            <th class="p-2">Tanggal</th>
                            <th class="p-2">Jam</th>
                            <th class="p-2">Harga</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            <tr class="border-b">
                                <td class="p-2">{{ $schedule->movie->title }}</td>
                                <td class="p-2">{{ $schedule->cinema->name }} <span class="text-xs text-gray-400">({{ $schedule->cinema->brand }})</span></td>
                                <td class="p-2">{{ $schedule->show_date->format('d M Y') }}</td>
                                <td class="p-2">{{ \Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i') }}</td>
                                <td class="p-2">Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-400">Belum ada jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $schedules->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>