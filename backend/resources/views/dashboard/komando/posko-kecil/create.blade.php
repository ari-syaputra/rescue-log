@extends('layouts.app')

@section('title', 'Tambah Posko Kecil - SiGap BPBD')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    {{-- Flash Notification & Catch Error Validasi --}}
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium space-y-1">
            <div class="flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                <span>⚠️ Form Belum Lengkap / Gagal Validasi:</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Posko Lapangan
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs font-medium text-slate-500">Registrasi Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Sub-Posko Kecil</h1>
            <p class="text-xs text-slate-500">Lengkapi informasi operasional posko dan tentukan titik koordinat presisi pada peta.</p>
        </div>
        <div>
            <a href="{{ route('komando.posko-kecil.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- Form Utama --}}
    <form action="{{ route('komando.posko-kecil.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- PERBAIKAN: Gunakan bencana_id dinamis dari Posko Komando / Bencana Aktif --}}
        @php
            $bencanaIdAktif = $komandoPosko->bencana_id ?? ($bencanaAktif->first()->id ?? 1);
        @endphp
        <input type="hidden" name="bencana_id" value="{{ $bencanaIdAktif }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- KOLOM KIRI: Data Operasional & Kontak --}}
            <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
                
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900">Detail Informasi Posko</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi identitas dan penanggung jawab lapangan</p>
                </div>

                <div class="space-y-4">
                    {{-- Nama Posko & Jumlah Petugas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Posko <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_posko" value="{{ old('nama_posko') }}" placeholder="Contoh: Posko Lapangan A" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all" required>
                            @error('nama_posko') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jumlah Petugas
                            </label>
                            <div class="relative">
                                <input type="number" name="jumlah_petugas" value="{{ old('jumlah_petugas') }}" placeholder="10" class="w-full pr-3.5 pl-9 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            </div>
                            @error('jumlah_petugas') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Penanggung Jawab & HP --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Penanggung Jawab <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" placeholder="Nama Koordinator" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all" required>
                            @error('penanggung_jawab') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. WhatsApp/HP
                            </label>
                            <input type="text" name="kontak_hp" value="{{ old('kontak_hp') }}" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                            @error('kontak_hp') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Blade Component Upload Foto --}}
                    <x-sub-posko.image-picker name="foto" label="Foto Dokumentasi Posko" />

                    {{-- Alamat / Detail Lokasi --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat / Patokan Lokasi
                        </label>
                        <textarea name="lokasi" rows="3" placeholder="Nama dusun, RT/RW, atau patokan lokasi terdekat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all resize-none">{{ old('lokasi') }}</textarea>
                    </div>

                </div>

            </div>

            {{-- KOLOM KANAN: Peta Interactive Marker --}}
            <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
                
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Titik Koordinat Lokasi</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Tentukan posisi geografis posko pada peta bawah ini</p>
                    </div>
                </div>

                {{-- Blade Component Map --}}
                <div class="rounded-xl overflow-hidden border border-slate-200">
                    <x-sub-posko.maps.picker height="380px" />
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('komando.posko-kecil.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-indigo-200 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan & Buat Kode Akses
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection