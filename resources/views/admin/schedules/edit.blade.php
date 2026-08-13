<x-app-layout>
    <x-slot name="header">
        Edit Jadwal Film
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-admin.card>
                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-admin.select name="movie_id" label="Film" required>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}" @selected(old('movie_id', $schedule->movie_id) == $movie->id)>
                                {{ $movie->title }}
                            </option>
                        @endforeach
                    </x-admin.select>

                    <x-admin.select name="cinema_id" label="Bioskop" required>
                        @foreach ($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" @selected(old('cinema_id', $schedule->cinema_id) == $cinema->id)>
                                {{ $cinema->name }} ({{ $cinema->brand }})
                            </option>
                        @endforeach
                    </x-admin.select>

                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.input type="date" name="show_date" label="Tanggal Tayang" required
                            :value="$schedule->show_date->format('Y-m-d')" />
                        <x-admin.input type="time" name="show_time" label="Jam Tayang" required
                            :value="\Illuminate\Support\Carbon::parse($schedule->show_time)->format('H:i')" />
                    </div>

                    <x-admin.input type="number" name="price" label="Harga Tiket (Rp)" required min="0" step="1000"
                        :value="$schedule->price" />

                    <div class="flex gap-2 pt-2">
                        <x-admin.button type="submit">Update Jadwal</x-admin.button>
                        <x-admin.button variant="secondary" href="{{ route('admin.schedules.index') }}">Batal</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-app-layout>