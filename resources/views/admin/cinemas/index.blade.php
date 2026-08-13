<x-app-layout>
    <x-slot name="header">
        Kelola Bioskop
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-admin.card>
                <x-admin.alert />

                <div class="flex justify-between items-center mb-5">
                    <p class="text-sm text-gray-400">{{ $cinemas->total() }} bioskop terdaftar</p>
                    <x-admin.button href="{{ route('admin.cinemas.create') }}">
                        + Tambah Bioskop
                    </x-admin.button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-white/10 text-gray-400">
                                <th class="p-3 font-medium">Nama Cabang</th>
                                <th class="p-3 font-medium">Brand</th>
                                <th class="p-3 font-medium">Kota</th>
                                <th class="p-3 font-medium">Alamat</th>
                                <th class="p-3 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cinemas as $cinema)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                                    <td class="p-3 text-white font-medium">{{ $cinema->name }}</td>
                                    <td class="p-3"><x-admin.badge variant="info">{{ $cinema->brand }}</x-admin.badge></td>
                                    <td class="p-3 text-gray-400">{{ $cinema->city }}</td>
                                    <td class="p-3 text-gray-400">{{ Str::limit($cinema->address, 40) }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            <x-admin.button variant="link" href="{{ route('admin.cinemas.edit', $cinema) }}">Edit</x-admin.button>
                                            <form action="{{ route('admin.cinemas.destroy', $cinema) }}" method="POST"
                                                  onsubmit="return confirm('Yakin hapus bioskop &quot;{{ $cinema->name }}&quot;? Semua jadwal terkait ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.button type="submit" variant="danger">Hapus</x-admin.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-gray-500">
                                        Belum ada data bioskop. Klik <span class="text-[#E50914]">"+ Tambah Bioskop"</span> untuk mulai menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 [&_button]:text-gray-300 [&_span]:text-gray-500">
                    {{ $cinemas->links() }}
                </div>
            </x-admin.card>
        </div>
    </div>
</x-app-layout>