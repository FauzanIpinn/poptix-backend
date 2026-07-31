<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, {{ auth()->user()->name }}! Anda login sebagai <strong>Admin</strong>.
                    <p class="mt-4">
                        <a href="{{ route('admin.movies.index') }}" class="text-indigo-600 underline">Kelola Film</a> |
                        <a href="{{ route('admin.cinemas.index') }}" class="text-indigo-600 underline">Kelola Bioskop</a> |
                        <a href="{{ route('admin.schedules.index') }}" class="text-indigo-600 underline">Kelola Jadwal</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>