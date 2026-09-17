@extends('layouts.app')

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapBencana {
            width: 100% !important;
            min-height: 420px !important;
            height: 420px !important;
            z-index: 1;
        }

        .leaflet-container .leaflet-tile-container img {
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.05);
            outline: 1px solid transparent;
            image-rendering: -webkit-optimize-contrast;
            mix-blend-mode: multiply;
        }

        .leaflet-interactive:focus {
            outline: none !important;
        }
        path.leaflet-interactive {
            outline: none !important;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium space-y-1">
            <div class="flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                <span>⚠️ Validasi Gagal:</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 1. Header & Stat Cards -->
    @include('components.admin.bencana.stats')

    <!-- 2. Main Grid (Peta & Deteksi Otomatis) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Peta Sebaran -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col">
            @include('components.admin.bencana.map')
        </div>

        <!-- Deteksi Otomatis (Pending) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col">
            @include('components.admin.bencana.pending-list')
        </div>
    </div>

    <!-- 3. Tabel Operasi Aktif -->
    @include('components.admin.bencana.active-table')

    <!-- 4. Tabel Riwayat Selesai -->
    @include('components.admin.bencana.completed-table')

</div>
@endsection

@push('scripts')
    @include('components.admin.bencana.scripts')
@endpush