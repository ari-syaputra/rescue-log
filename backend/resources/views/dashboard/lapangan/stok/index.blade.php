@extends('layouts.app-lapangan')

@section('content')
    <div class="space-y-6">

        <x-sub-posko.page-header title="Status distribusi dan stok logistik"
            description="Pantau status pengiriman dari Posko Komando serta ketersediaan stok barang di pos lapangan">
<a href="{{ route('lapangan.pengajuan.create') }}"
    class="ml-auto inline-flex items-center px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-sm font-medium transition shadow-sm gap-2">
    <x-heroicon-s-plus class="w-5 h-5 text-white shrink-0 stroke-[3.5]" />
    <span>Pengajuan Baru</span>
</a>
        </x-sub-posko.page-header>

        <div class="w-full space-y-6" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 400)">

            <div x-show="isLoading" class="w-full" style="display: none;">
                <x-skeleton.tabel-distribusi-loading />
            </div>

            <div x-show="!isLoading" class="space-y-6 w-full" style="display: none;">
                <x-sub-posko.distribusi.status-pengiriman-table :pengirimans="$pengirimans" />
                <x-sub-posko.distribusi.inventaris-stok-table :stoks="$stoks" />
            </div>

        </div>

    </div>

    <x-sub-posko.distribusi.modal-distribusi-script />
@endsection
