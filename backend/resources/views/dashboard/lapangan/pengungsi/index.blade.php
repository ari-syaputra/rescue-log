@extends('layouts.app-lapangan')

@section('content')
    <div class="w-full space-y-6" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 400)">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <!-- Bagian Teks Header -->
            <x-sub-posko.page-header title="Pendataan Pengungsi"
                description="Pantau demografi dan riwayat pengungsi di posko Anda.">
            </x-sub-posko.page-header>

            <!-- Tombol dengan margin atas khusus layar HP (mt-4) agar tidak menimpa teks -->
            <div class="w-full sm:w-auto mt-4 sm:mt-0">
                <button onclick="openPendataanModal()"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-700 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-800 transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Perbarui Data Pengungsi
                </button>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl text-sm font-medium mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div x-show="isLoading" class="space-y-6 w-full" style="display: none;">
            <x-skeleton.loading />
        </div>

        <div x-show="!isLoading" class="w-full space-y-6">
            @if ($isFirstTime)
                <x-skeleton.kosong />
            @else
                <div class="space-y-6 w-full">
                    <x-sub-posko.stat-card :pendataan-terakhir="$pendataan_terakhir" />

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <x-heroicon-s-clock class="w-5 h-5" />
                                </div>
                                <h3 class="font-bold text-gray-800">Riwayat Perubahan Data</h3>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Total: {{ count($riwayat_pendataan) }} data</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-gray-500 bg-white border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-calendar class="w-4 h-4 text-gray-400" /> Tanggal Update
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-users class="w-4 h-4 text-blue-500" /> Total Pengungsi
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-building-office-2 class="w-4 h-4 text-emerald-500" /> Tipe Fasilitas
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold text-right">
                                            <span class="inline-flex items-center justify-end gap-1.5">
                                                <x-heroicon-s-sun class="w-4 h-4 text-amber-500" /> Cuaca Tercatat
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($riwayat_pendataan as $riwayat)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-blue-600 font-bold">
                                                {{ $riwayat->total_pengungsi ?? 0 }} Jiwa
                                            </td>
                                            <td class="px-6 py-4 text-gray-700">
                                                {{ $riwayat->tipe_tempat ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-500">
                                                {{ $riwayat->cuaca ?? '-' }} 
                                                @if(isset($riwayat->suhu_celcius))
                                                    <span class="text-xs text-gray-400">({{ $riwayat->suhu_celcius }}°C)</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                                Belum ada riwayat perubahan data.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @include('dashboard.lapangan.pengungsi._modal_form')

    </div>
@endsection