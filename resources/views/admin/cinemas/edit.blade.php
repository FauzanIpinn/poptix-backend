<x-app-layout>
    <x-slot name="header">
        Edit Bioskop
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-admin.card>
                <form action="{{ route('admin.cinemas.update', $cinema) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-admin.input name="name" label="Nama Cabang" required :value="$cinema->name" />

                    <x-admin.select name="brand" label="Brand" required>
                        <option value="XXI" @selected(old('brand', $cinema->brand) === 'XXI')>XXI</option>
                        <option value="CGV" @selected(old('brand', $cinema->brand) === 'CGV')>CGV</option>
                        <option value="Cinepolis" @selected(old('brand', $cinema->brand) === 'Cinepolis')>Cinepolis</option>
                    </x-admin.select>

                    <x-admin.input name="city" label="Kota" required :value="$cinema->city" />

                    <x-admin.textarea name="address" label="Alamat Lengkap" required rows="3" :value="$cinema->address" />

                    <div class="flex gap-2 pt-2">
                        <x-admin.button type="submit">Update Bioskop</x-admin.button>
                        <x-admin.button variant="secondary" href="{{ route('admin.cinemas.index') }}">Batal</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-app-layout>