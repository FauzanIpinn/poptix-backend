<x-app-layout>
    <x-slot name="header">
        Tambah Bioskop
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-admin.card>
                <form action="{{ route('admin.cinemas.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <x-admin.input name="name" label="Nama Cabang" required placeholder="cth: XXI Malang Town Square"
                        hint="Setiap bioskop baru otomatis mendapat 50 kursi (baris A–E, nomor 1–10)." />

                    <x-admin.select name="brand" label="Brand" required>
                        <option value="" disabled selected>-- Pilih Brand --</option>
                        <option value="XXI" @selected(old('brand') === 'XXI')>XXI</option>
                        <option value="CGV" @selected(old('brand') === 'CGV')>CGV</option>
                        <option value="Cinepolis" @selected(old('brand') === 'Cinepolis')>Cinepolis</option>
                    </x-admin.select>

                    <x-admin.input name="city" label="Kota" required placeholder="cth: Malang" />

                    <x-admin.textarea name="address" label="Alamat Lengkap" required rows="3"
                        placeholder="Jl. Veteran No. 2, Malang" />

                    <div class="flex gap-2 pt-2">
                        <x-admin.button type="submit">Simpan Bioskop</x-admin.button>
                        <x-admin.button variant="secondary" href="{{ route('admin.cinemas.index') }}">Batal</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-app-layout>