@extends('layouts.app-lapangan')

@section('content')
    <div class="space-y-6" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 1200)">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <x-sub-posko.page-header title="Penyaluran & Pencatatan Stok"
                description="Catat logistik yang disalurkan langsung kepada pengungsi dan pantau pengurangan stok.">
            </x-sub-posko.page-header>

            <a href="#"
                class="inline-flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                + Catat Penyaluran Baru
            </a>
        </div>

        <div x-show="isLoading" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @include('components.skeleton.card')
                @include('components.skeleton.card')
                @include('components.skeleton.card')
            </div>

            @include('components.skeleton.table')
        </div>

        <div x-show="!isLoading" style="display: none;" class="space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Penyaluran Hari Ini</p>
                        <h4 class="text-xl font-bold text-gray-800">38 Paket</h4>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Penerima Manfaat</p>
                        <h4 class="text-xl font-bold text-gray-800">38 KK</h4>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Jenis Stok Tersedia</p>
                        <h4 class="text-xl font-bold text-gray-800">24 Jenis</h4>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="py-3.5 px-6">ID / Waktu Catat</th>
                                <th class="py-3.5 px-6">Penerima (Kepala Keluarga)</th>
                                <th class="py-3.5 px-6">Item & Jumlah Logistik Disalurkan</th>
                                <th class="py-3.5 px-6">Petugas Lapangan</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-900">#DIST-001</span>
                                    <p class="text-xs text-gray-400 mt-0.5">17 Mei 2024, 10:30 WIB</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium text-gray-800">Bapak Ahmad Zaelani</span>
                                    <p class="text-xs text-gray-400">Dusun Sukamaju (5 Jiwa)</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-gray-800">Beras (5 kg), Mie Instan (1 dus), Air Mineral (1
                                        galon)</span>
                                </td>
                                <td class="py-4 px-6 text-gray-600">Petugas Budi</td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="#"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">Detail</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-900">#DIST-002</span>
                                    <p class="text-xs text-gray-400 mt-0.5">17 Mei 2024, 08:45 WIB</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium text-gray-800">Ibu Siti Aminah</span>
                                    <p class="text-xs text-gray-400">Dusun Sukamaju (3 Jiwa)</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-gray-800">Beras (5 kg), Susu Bayi (1 kotak)</span>
                                </td>
                                <td class="py-4 px-6 text-gray-600">Petugas Budi</td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="#"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
                    <span>Menampilkan riwayat pencatatan penyaluran logistik</span>
                    <div class="flex items-center space-x-1">
                        <span
                            class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-400 cursor-not-allowed">Sebelumnya</span>
                        <span class="px-3 py-1 rounded bg-amber-600 text-white font-medium">1</span>
                        <span
                            class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-400 cursor-not-allowed">Selanjutnya</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
