@extends('layouts.app-lapangan')

@section('content')
    <div class="space-y-6" x-data="{ isLoading: true, showModal: false }" x-init="setTimeout(() => isLoading = false, 600)">

<x-sub-posko.page-header title="Penyaluran dan Pencatatan Stok"
    description="Catat logistik yang disalurkan langsung kepada pengungsi dan pantau pengurangan stok.">

    <button @click="showModal = true"
        class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2.5 rounded-xl text-sm shadow-sm transition gap-2 shrink-0 cursor-pointer ml-auto">
        <x-heroicon-s-plus class="w-5 h-5 text-white shrink-0 stroke-[3.5]" />
        <span>Catat Penyaluran Baru</span>
    </button>
</x-sub-posko.page-header>

        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

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
                <x-sub-posko.penyaluran.stat-card title="Total Riwayat Penyaluran" :value="$riwayatPenyaluran->total() . ' Transaksi'" bg-icon="bg-amber-50"
                    text-icon="text-amber-600">
                    <x-heroicon-s-clipboard-document-check class="w-6 h-6 text-amber-600" />
                </x-sub-posko.penyaluran.stat-card>

                <x-sub-posko.penyaluran.stat-card title="Stok Siap Salur" :value="$stoks->count() . ' Item'" bg-icon="bg-blue-50"
                    text-icon="text-blue-600">
                    <x-heroicon-s-cube class="w-6 h-6 text-blue-600" />
                </x-sub-posko.penyaluran.stat-card>

                <x-sub-posko.penyaluran.stat-card title="Status Sistem" value="Aktif" bg-icon="bg-emerald-50"
                    text-icon="text-emerald-600" text-value="text-emerald-600">
                    <x-heroicon-s-check-circle class="w-6 h-6 text-emerald-600" />
                </x-sub-posko.penyaluran.stat-card>
            </div>
            <x-sub-posko.penyaluran.table-penyaluran :riwayatPenyaluran="$riwayatPenyaluran" />

        </div>

        <x-sub-posko.penyaluran.modal-penyaluran :stoks="$stoks" />

    </div>
@endsection
