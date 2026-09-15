@extends('layouts.app')

@section('title', 'Tambah Posko Kecil - SiGap BPBD')

@section('content')
    <div class="w-full space-y-6 pb-12">

        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium space-y-1">
                <div class="flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                    <span class="flex items-center gap-1.5">
                        <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-rose-600" />
                        Form Belum Lengkap / Gagal Validasi:
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="text-rose-500 hover:text-rose-800">&times;</button>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-2">
            <div class="flex items-start gap-3">
                <a href="{{ route('komando.posko-kecil.index') }}"
                    class="p-2 rounded-xl bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all shadow-2xs shrink-0 mt-0.5"
                    title="Kembali ke Daftar">
                    <x-heroicon-s-arrow-left class="w-5 h-5" />
                </a>

                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Sub-Posko Kecil</h1>
                    <p class="text-xs text-slate-500">Lengkapi informasi operasional sub posko dan tentukan titik koordinat
                        presisi pada peta.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('komando.posko-kecil.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @php
                $bencanaIdAktif = $komandoPosko->bencana_id ?? ($bencanaAktif->first()->id ?? 1);
            @endphp
            <input type="hidden" name="bencana_id" value="{{ $bencanaIdAktif }}">

            {{-- Container Utama: Menggunakan items-stretch agar tinggi card kiri & kanan otomatis sama --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                {{-- CARD KIRI: Detail Informasi Posko --}}
                <div
                    class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                    <div class="flex flex-col h-full">
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="text-base font-bold text-slate-900">Detail Informasi Posko</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Informasi identitas dan penanggung jawab lapangan</p>
                        </div>

                        {{-- Dibuat flex flex-col flex-1 agar turunan di dalamnya bisa mengisi sisa ruang vertikal --}}
                        <div class="space-y-4 flex-1 flex flex-col">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nama Posko <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="nama_posko" value="{{ old('nama_posko') }}"
                                        placeholder="Contoh: Posko Lapangan A"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all"
                                        required>
                                    @error('nama_posko')
                                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Jumlah Petugas
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="jumlah_petugas" value="{{ old('jumlah_petugas') }}"
                                            placeholder="10"
                                            class="w-full pr-3.5 pl-9 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <x-heroicon-s-user-group class="w-4 h-4" />
                                        </div>
                                    </div>
                                    @error('jumlah_petugas')
                                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Penanggung Jawab <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}"
                                        placeholder="Nama Koordinator"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all"
                                        required>
                                    @error('penanggung_jawab')
                                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        No. WhatsApp/HP
                                    </label>
                                    <input type="text" name="kontak_hp" value="{{ old('kontak_hp') }}"
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                                    @error('kontak_hp')
                                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-sub-posko.image-picker name="foto" label="Foto Dokumentasi Posko" />

                            {{-- Textarea Alamat melebarkan ukurannya secara otomatis hingga dasar card --}}
                            <div class="flex-1 flex flex-col min-h-[100px]">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Alamat / Patokan Lokasi
                                </label>
                                <textarea name="lokasi" placeholder="Nama dusun, RT/RW, atau patokan lokasi terdekat"
                                    class="w-full h-full flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all resize-none">{{ old('lokasi') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD KANAN: Peta Interactive Marker --}}
                <div
                    class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
                    <div class="space-y-4 flex-1 flex flex-col">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-base font-bold text-slate-900">Titik Koordinat Lokasi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Tentukan posisi geografis posko pada peta bawah ini</p>
                        </div>

                        {{-- Map otomatis mengisi sisa ruang vertikal --}}
                        <div class="rounded-xl overflow-hidden border border-slate-200 flex-1 min-h-[350px]">
                            <x-sub-posko.maps.picker height="380px" />
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100 shrink-0">
                        <!-- Tombol Batal -->
                        <a href="{{ route('komando.posko-kecil.index') }}"
                            class="h-10 inline-flex items-center justify-center px-6 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg focus:ring-4 focus:ring-slate-100 transition shadow-xs whitespace-nowrap cursor-pointer">
                            Batal
                        </a>

                        <!-- Tombol Simpan -->
                        <button type="submit"
                            class="h-10 inline-flex items-center justify-center px-6 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-indigo-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                            Simpan dan Buat Kode Akses
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
