@extends('layouts.app-lapangan')

@section('content')
<div class="w-full space-y-6">

    <!-- 1. HEADER HALAMAN -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('lapangan.dashboard') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Pengajuan Kebutuhan Logistik</h1>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        REKOMENDASI AI
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Angka kebutuhan di bawah dikalkulasi otomatis oleh Machine Learning AI </p>
                <p class="text-sm text-slate-500">berdasarkan jumlah pengungsi, kategori usia, dan kondisi cuaca terkini.</p>
            </div>
        </div>

        <button type="submit" form="form-pengajuan" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 transition cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            <span>Kirim Pengajuan</span>
        </button>
    </div>

    <!-- 2. HERO BANNER FULL GAMBAR & DATA ACUAN LOGISTIK -->
    <div class="relative w-full rounded-2xl overflow-hidden bg-linear-to-r from-sky-100 via-blue-100 to-indigo-100 border border-blue-200/60 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xs min-h-50">
        
        <!-- Background Image Full Span -->
        <div class="absolute inset-0 w-full h-full z-0 pointer-events-none flex justify-end overflow-hidden">
            <img src="{{ asset('img/hero-logistik.png') }}" alt="Ilustrasi Logistik" class="h-full w-full object-cover object-bottom-right opacity-90">
        </div>

        <!-- Sisi Kiri: Rincian Data Pengungsi -->
        <div class="flex-1 space-y-3 z-10 max-w-xl">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-600/10 text-blue-700 border border-blue-300/40 text-[11px] font-bold tracking-wider uppercase backdrop-blur-xs">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>REKOMENDASI AI POSKO</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Total {{ $pendataan->total_pengungsi ?? 0 }} Pengungsi
            </h2>

            <!-- Badges Kategori Pengungsi -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/90 backdrop-blur-md border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    Balita: <strong class="text-slate-900">{{ $pendataan->balita ?? 0 }}</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/90 backdrop-blur-md border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Lansia: <strong class="text-slate-900">{{ $pendataan->lansia ?? 0 }}</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/90 backdrop-blur-md border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Ibu Hamil: <strong class="text-slate-900">{{ $pendataan->ibu_hamil ?? 0 }}</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/90 backdrop-blur-md border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Disabilitas: <strong class="text-slate-900">{{ $pendataan->disabilitas ?? 0 }}</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/90 backdrop-blur-md border border-slate-200/80 text-xs font-semibold text-slate-700 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Tempat: <strong class="text-slate-900">{{ $pendataan->tipe_tempat ?? 'Balai Desa' }}</strong>
                </span>
            </div>
        </div>

        <!-- Sisi Kanan: Card Cuaca BMKG -->
        <div class="bg-white/90 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-white/80 shadow-sm shrink-0 w-full md:w-56 text-center md:text-left z-10">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Sumber: BMKG</div>
            <div class="flex items-center justify-center md:justify-start gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-sm">{{ $pendataan->cuaca ?? 'Cerah / Berawan' }}</div>
                    <div class="text-lg font-black text-blue-600">{{ $pendataan->suhu_celcius ?? 29.8 }} °C</div>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 mt-2 text-center md:text-left">Data cuaca diambil otomatis</div>
        </div>

    </div>

    <!-- 3. FORM INPUT KEBUTUHAN LOGISTIK -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        
        <!-- Header Section Form -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Item Kebutuhan Logistik (Dapat Disesuaikan)</h3>
            </div>

            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-xs text-blue-600 font-semibold bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rekomendasi kebutuhan dihitung otomatis oleh AI
                </span>
                <a href="{{ route('lapangan.pengungsi.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-blue-600 bg-slate-100 hover:bg-slate-200/80 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Perbarui Data
                </a>
            </div>
        </div>

        <form id="form-pengajuan" action="{{ route('lapangan.pengajuan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- GRID 12 CARD ITEM LOGISTIK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Beras -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-blue-100/70 text-blue-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Beras</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" step="0.01" name="beras_kg" value="{{ round($estimasi['beras_kg'] ?? 136.9, 1) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">KG</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['beras_kg'] ?? 136.9, 1) }} kg</div>
                    </div>
                </div>

                <!-- 2. Air Minum -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-sky-100/70 text-sky-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14m-6 0a6 6 0 10-12 0 6 6 0 0012 0zM12 3v11"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Air Minum</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="air_minum_dus" value="{{ round($estimasi['air_minum_dus'] ?? 71) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">DUS</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['air_minum_dus'] ?? 71) }} Dus</div>
                    </div>
                </div>

                <!-- 3. Makanan Kaleng -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-emerald-100/70 text-emerald-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Makanan Kaleng</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="makanan_kaleng_pack" value="{{ round($estimasi['makanan_kaleng_pack'] ?? 658) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PACK</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['makanan_kaleng_pack'] ?? 658) }} Pack</div>
                    </div>
                </div>

                <!-- 4. Makanan Bayi -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-pink-100/70 text-pink-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Makanan Bayi</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="makanan_bayi_pack" value="{{ round($estimasi['makanan_bayi_pack'] ?? 180) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PACK</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['makanan_bayi_pack'] ?? 180) }} Pack</div>
                    </div>
                </div>

                <!-- 5. Minyak Goreng -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-amber-100/70 text-amber-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.605 15.12a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Minyak Goreng</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" step="0.1" name="minyak_goreng_liter" value="{{ round($estimasi['minyak_goreng_liter'] ?? 33.6, 1) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">LITER</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['minyak_goreng_liter'] ?? 33.6, 1) }} L</div>
                    </div>
                </div>

                <!-- 6. Popok Bayi -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-purple-100/70 text-purple-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Popok Bayi</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="popok_bayi_pcs" value="{{ round($estimasi['popok_bayi_pcs'] ?? 213) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PCS</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['popok_bayi_pcs'] ?? 213) }} Pcs</div>
                    </div>
                </div>

                <!-- 7. Popok Dewasa -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-teal-100/70 text-teal-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Popok Dewasa</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="popok_dewasa_pcs" value="{{ round($estimasi['popok_dewasa_pcs'] ?? 85) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PCS</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['popok_dewasa_pcs'] ?? 85) }} Pcs</div>
                    </div>
                </div>

                <!-- 8. Pembalut Wanita -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-rose-100/70 text-rose-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Pembalut Wanita</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="pembalut_wanita_pack" value="{{ round($estimasi['pembalut_wanita_pack'] ?? 9) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PACK</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['pembalut_wanita_pack'] ?? 9) }} Pack</div>
                    </div>
                </div>

                <!-- 9. Hygiene Kit -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-indigo-100/70 text-indigo-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.605 15.12a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Hygiene Kit</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="hygiene_kit_paket" value="{{ round($estimasi['hygiene_kit_paket'] ?? 18) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PAKET</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['hygiene_kit_paket'] ?? 18) }} Paket</div>
                    </div>
                </div>

                <!-- 10. Selimut -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-blue-100/70 text-blue-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Selimut</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="selimut_pcs" value="{{ round($estimasi['selimut_pcs'] ?? 62) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PCS</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['selimut_pcs'] ?? 62) }} Pcs</div>
                    </div>
                </div>

                <!-- 11. Matras / Terpal -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-lime-100/70 text-lime-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Matras / Terpal</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="matras_terpal_pcs" value="{{ round($estimasi['matras_terpal_pcs'] ?? 18) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PCS</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['matras_terpal_pcs'] ?? 18) }} Pcs</div>
                    </div>
                </div>

                <!-- 12. Obat P3K -->
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between gap-3 hover:border-blue-300 transition">
                    <div class="p-3 bg-orange-100/70 text-orange-600 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700">Obat P3K</div>
                        <div class="flex items-baseline justify-between mt-1">
                            <input type="number" name="obat_p3k_paket" value="{{ round($estimasi['obat_p3k_paket'] ?? 3) }}" class="w-24 text-xl font-black text-slate-900 bg-transparent outline-none" required>
                            <span class="text-xs font-bold text-slate-400">PAKET</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-semibold mt-1">Rekomendasi AI: {{ round($estimasi['obat_p3k_paket'] ?? 3) }} Paket</div>
                    </div>
                </div>

            </div>

            <!-- CATATAN TAMBAHAN POSKO (OPSIONAL) -->
            <div class="pt-4 space-y-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <label class="block text-xs font-bold text-slate-700">Catatan Tambahan Posko (Opsional)</label>
                </div>
                <textarea name="catatan_posko" rows="3" maxlength="500" placeholder="Tuliskan catatan khusus atau alasan jika ada penyesuaian angka di atas..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50/40"></textarea>
                <div class="text-right text-[11px] text-slate-400 font-medium">0/500 karakter</div>
            </div>

            <!-- FOOTER BANNER INFORMASI AI -->
            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
                <div class="p-2 bg-blue-600 text-white rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="space-y-0.5 text-xs">
                    <div class="font-bold text-slate-900">Dihitung oleh AI: <span class="font-normal text-slate-600">Kebutuhan logistik di atas merupakan rekomendasi yang dihitung otomatis oleh Machine Learning AI berdasarkan komposisi pengungsi, lama pengungsian, dan kondisi lokasi.</span></div>
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-bold inline-flex items-center gap-1">
                        Pelajari lebih lanjut tentang perhitungan AI
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

        </form>
    </div>

</div>

<!-- CDN SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- NOTIFIKASI SWEETALERT2 INTEGRATED -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#DC2626',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                }
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                title: 'Perhatian',
                text: "{{ session('warning') }}",
                icon: 'warning',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#D97706',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                }
            });
        @endif
    });
</script>
@endsection