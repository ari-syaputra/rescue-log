@extends('layouts.app')

@section('title', 'Daftar Posko Kecil - SiGap BPBD')

@section('content')
    <div class="w-full space-y-6 pb-12">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Sub-Posko Kecil</h1>
                <p class="text-xs text-slate-500">Kelola informasi posko lapangan, titik koordinat, dan kode akses akun
                    petugas.</p>
            </div>
            <div>
                <a href="{{ route('komando.posko-kecil.create') }}"
                    class="w-full md:w-auto shrink-0 h-10 inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-700 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-blue-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    Tambah Sub-Posko
                </a>
            </div>
        </div>


        <x-sub-posko.stats-bar :totalPosko="$totalPosko ?? $subPoskos->total()" :poskoAktif="$poskoAktif ?? 0" :totalPetugas="$totalPetugas ?? 0" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <div class="lg:col-span-8 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-base font-bold text-slate-900">Daftar Posko Kecil</h2>
                    <span class="text-xs text-slate-500 font-medium">{{ $subPoskos->total() }} Posko</span>
                </div>

                @forelse($subPoskos as $posko)
                    <x-sub-posko.card :posko="$posko" />
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center space-y-3">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4" />
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Belum ada Sub-Posko yang terdaftar.</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if ($subPoskos->hasPages())
                    <div
                        class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-500">
                            Menampilkan <span class="font-semibold text-slate-800">{{ $subPoskos->firstItem() }}</span> -
                            <span class="font-semibold text-slate-800">{{ $subPoskos->lastItem() }}</span> dari <span
                                class="font-semibold text-slate-800">{{ $subPoskos->total() }}</span> Posko
                        </div>
                        {{ $subPoskos->links() }}
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN: Sidebar Widgets --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Map Component --}}
                <x-sub-posko.map-widget :poskos="$subPoskos->items()" :poskoAktif="$poskoAktif ?? 0" :poskoSiaga="$poskoSiaga ?? 0" :poskoNonaktif="$poskoNonaktif ?? 0" />
            </div>

        </div>
    </div>
@endsection
