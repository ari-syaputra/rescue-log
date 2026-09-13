@extends('layouts.app')

@section('content')
<div x-data="{ 
    modalTambah: false, 
    modalEdit: false, 
    editData: { id: '', nama_barang: '', kategori: '', jumlah: 0, satuan: '', keterangan: '' }
}">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Stok Inventaris (BPBD)</h1>
            <p class="text-base text-gray-600 mt-1">Kelola dan pantau seluruh ketersediaan logistik dan peralatan bantuan bencana secara real-time.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="modalTambah = true" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-700 hover:bg-blue-800 shadow-md transition cursor-pointer">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Stok Barang
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
        <span class="font-medium text-sm">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-600 font-bold text-lg cursor-pointer">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
        <span class="font-medium text-sm">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-600 font-bold text-lg cursor-pointer">&times;</button>
    </div>
    @endif

    <!-- Cards Stats Ketersediaan Barang -->
    <x-admin.inventaris.stats-cards :stokInventaris="$stokInventaris" />

    <!-- Action Bar & Tabel Data Stok -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden mb-8">
        <x-admin.inventaris.action-bar />
        <x-admin.inventaris.table :stokInventaris="$stokInventaris" />
    </div>

    <!-- Modal Form Tambah & Edit Stok -->
    <x-admin.inventaris.modal-add />
    <x-admin.inventaris.modal-edit />

</div>
@endsection