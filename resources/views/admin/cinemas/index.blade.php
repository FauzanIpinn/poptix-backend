<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Bioskop</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('admin.cinemas.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded">
                    + Tambah Bioskop
                </a>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="p-2">Nama</th>
                            <th class="p-2">Brand</th>
                            <th class="p-2">Kota</th>
                            <th class="p-2">Alamat</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cinemas as $cinema)
                            <tr class="border-b">
                                <td class="p-2">{{ $cinema->name }}</td>
                                <td class="p-2">
                                    <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-700">
                                        {{ $cinema->brand }}
                                    </span>
                                </td>
                                <td class="p-2">{{ $cinema->city }}</td>
                                <td class="p-2">{{ Str::limit($cinema->address, 40) }}</td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('admin.cinemas.edit', $cinema) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('admin.cinemas.destroy', $cinema) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus bioskop ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-400">Belum ada data bioskop.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $cinemas->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>