@extends('layouts.admin')

@section('header', 'Denah Kursi Studio')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Visualisasi Denah - {{ $cinema->name }}</h2>
            <p class="text-ticketor-gray text-sm mt-1">Pratinjau susunan tata letak kursi otomatis (80 Kursi).</p>
        </div>
        <a href="{{ route('admin.cinemas.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
            Kembali
        </a>
    </div>

    <div class="bg-ticketor-card p-8 rounded-xl border border-gray-800">
        <!-- Visual Layar Bioskop -->
        <div class="mb-10 text-center">
            <div class="w-3/4 mx-auto h-3 bg-gradient-to-b from-ticketor-neon to-transparent rounded-t-full opacity-60 mb-2"></div>
            <p class="text-xs text-ticketor-gray tracking-widest uppercase">LAYAR BIOSKOP</p>
        </div>

        <!-- Grid Kursi Studio -->
        <div class="space-y-3">
            @php
                $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            @endphp

            @foreach($rows as $row)
            <div class="flex items-center justify-center gap-2">
                <span class="w-6 text-xs font-bold text-ticketor-gray text-right mr-2">{{ $row }}</span>
                
                @for($i = 1; $i <= 10; $i++)
                    <!-- Gap Lorong Tengah Bioskop -->
                    @if($i == 6)
                        <div class="w-6"></div>
                    @endif

                    <div class="w-8 h-8 rounded-md bg-gray-800 border border-gray-700 flex items-center justify-center text-xs text-ticketor-gray font-mono hover:border-ticketor-neon hover:text-ticketor-neon cursor-pointer transition">
                        {{ $i }}
                    </div>
                @endfor
                
                <span class="w-6 text-xs font-bold text-ticketor-gray text-left ml-2">{{ $row }}</span>
            </div>
            @endforeach
        </div>

        <!-- Legenda Keterangan Kursi -->
        <div class="mt-10 pt-6 border-t border-gray-800 flex justify-center items-center gap-8 text-xs text-ticketor-gray">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-gray-800 border border-gray-700 rounded"></div>
                <span>Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-ticketor-neon rounded"></div>
                <span class="text-white">Terisi / Dipesan</span>
            </div>
        </div>
    </div>
</div>
@endsection