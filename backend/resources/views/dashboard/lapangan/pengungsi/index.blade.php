@extends('layouts.app-lapangan')

@section('content')
<div class="w-full space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <x-sub-posko.page-header title="Dashboard Pendataan Pengungsi"
            description="Pantau demografi dan riwayat pengungsi di posko Anda.">
        </x-sub-posko.page-header>
        
        <div class="mt-4 sm:mt-0">
            <!-- Tombol Trigger Modal -->
            <button onclick="openPendataanModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Perbarui Data Pengungsi
            </button>
        </div>
    </div>

    <!-- Alert Error -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tampilan Jika BELUM ADA DATA -->
    @if($isFirstTime)
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center w-full">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Belum Ada Data Pendataan</h3>
            <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">Posko Anda belum memiliki data pengungsi. Silakan lakukan pendataan pertama agar AI dapat mengkalkulasi kebutuhan logistik.</p>
        </div>
    @else
        <!-- Tampilan Jika SUDAH ADA DATA (Dashboard Mini) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full">
            <!-- Statistik 1: Total Pengungsi -->
            <div class="bg-blue-600 rounded-xl p-6 text-white shadow-sm md:col-span-2 flex flex-col justify-between">
                <div class="text-blue-100 text-sm font-medium mb-2 uppercase tracking-wide">Total Pengungsi Terkini</div>
                <div class="flex items-end justify-between">
                    <div class="text-5xl font-extrabold">{{ $pendataan_terakhir->total_pengungsi }} <span class="text-xl font-normal text-blue-200">Jiwa</span></div>
                    <div class="bg-blue-500/50 px-3 py-1 rounded text-xs">Update: {{ $pendataan_terakhir->created_at->diffForHumans() }}</div>
                </div>
            </div>

            <!-- Statistik 2 & 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-center">
                <div class="text-gray-500 text-sm font-medium mb-1">Anak Balita</div>
                <div class="text-2xl font-bold text-gray-900">{{ $pendataan_terakhir->balita }} Jiwa</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-center">
                <div class="text-gray-500 text-sm font-medium mb-1">Lansia & Rentan</div>
                <div class="text-2xl font-bold text-gray-900">{{ $pendataan_terakhir->lansia + $pendataan_terakhir->disabilitas + $pendataan_terakhir->ibu_hamil }} Jiwa</div>
            </div>
        </div>

        <!-- Tabel Riwayat Data -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-800">Riwayat Perubahan Data</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-gray-500 bg-white border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Tanggal Update</th>
                            <th class="px-6 py-3 font-semibold">Total Pengungsi</th>
                            <th class="px-6 py-3 font-semibold">Tipe Fasilitas</th>
                            <th class="px-6 py-3 font-semibold text-right">Cuaca Tercatat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($riwayat_pendataan as $riwayat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $riwayat->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-blue-600 font-bold">{{ $riwayat->total_pengungsi }} Jiwa</td>
                                <td class="px-6 py-4">{{ $riwayat->tipe_tempat }}</td>
                                <td class="px-6 py-4 text-right text-gray-500">{{ $riwayat->cuaca }} ({{ $riwayat->suhu_celcius }}°C)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- INCLUDE KOMPONEN MODAL -->
    @include('dashboard.lapangan.pengungsi._modal_form')

</div>

<!-- Auto-open Modal Logic jika $isFirstTime == true -->
@if($isFirstTime)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        openPendataanModal();
    });
</script>
@endif

@endsection