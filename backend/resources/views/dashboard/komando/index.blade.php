@extends('layouts.app')

@section('content')
<div class="w-full space-y-6 pb-10">

    <!-- HERO BANNER POSKO KOMANDO -->
    <div class="relative w-full rounded-2xl overflow-hidden bg-linear-to-r from-sky-100 via-blue-100 to-indigo-100 border border-blue-200/60 p-6 md:p-8 flex flex-col justify-between shadow-xs min-h-[320px]">
        
        <!-- Background Image Full Span (Sisi Kanan) -->
        <div class="absolute inset-0 w-full h-full z-0 pointer-events-none flex justify-end overflow-hidden">
            <img src="{{ asset('img/hero-komando.png') }}" alt="Posko Komando Hero" class="h-full w-full object-cover object-bottom-right opacity-95">
        </div>

        <!-- Sisi Atas: Teks Judul & Status Sistem -->
        <div class="relative z-10 space-y-4 max-w-xl">
            <div>
                <span class="text-xs font-extrabold text-blue-700 tracking-wider uppercase block mb-1">POSKO KOMANDO SIAGA</span>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Siap, Responsif, Terkoordinasi</h1>
                <p class="text-xs md:text-sm text-slate-600 mt-1 leading-relaxed">
                    Pantau situasi, kelola sumber daya, dan pastikan distribusi logistik berjalan tepat sasaran.
                </p>
            </div>

            <!-- Status Indicator -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-emerald-700 border border-emerald-200 shadow-xs backdrop-blur-md">
                    <span class="w-2 h-2 mr-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    Sistem Aktif
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-white/80 px-3 py-1 rounded-full border border-slate-200/80 shadow-xs backdrop-blur-md">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </span>
            </div>
        </div>

        <!-- Sisi Bawah: 4 Stat Badges di dalam Hero -->
        <div class="relative z-10 pt-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full max-w-4xl">
                
                <!-- Armada Siap -->
                <div class="bg-white/90 backdrop-blur-md border border-white/80 p-3 rounded-2xl shadow-xs flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Armada Siap</span>
                        <span class="text-base sm:text-lg font-black text-slate-900">12 <span class="text-xs font-semibold text-slate-500">Unit</span></span>
                    </div>
                </div>

                <!-- Personel Siaga -->
                <div class="bg-white/90 backdrop-blur-md border border-white/80 p-3 rounded-2xl shadow-xs flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Personel Siaga</span>
                        <span class="text-base sm:text-lg font-black text-slate-900">48 <span class="text-xs font-semibold text-slate-500">Orang</span></span>
                    </div>
                </div>

                <!-- Lokasi Terdampak -->
                <div class="bg-white/90 backdrop-blur-md border border-white/80 p-3 rounded-2xl shadow-xs flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-sky-50 text-sky-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Lokasi Terdampak</span>
                        <span class="text-base sm:text-lg font-black text-slate-900">4 <span class="text-xs font-semibold text-slate-500">Desa</span></span>
                    </div>
                </div>

                <!-- Logistik Terkirim -->
                <div class="bg-white/90 backdrop-blur-md border border-white/80 p-3 rounded-2xl shadow-xs flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Logistik Terkirim</span>
                        <span class="text-base sm:text-lg font-black text-slate-900">234 <span class="text-xs font-semibold text-slate-500">Paket</span></span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- 4 CARD RINGKASAN UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pengajuan Masuk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pengajuan Masuk</span>
                <span class="text-2xl font-black text-slate-900">{{ $totalPengajuanMasuk ?? 3 }}</span>
                <span class="text-[11px] text-slate-500 block mt-0.5">Menunggu keputusan komando</span>
            </div>
        </div>

        <!-- Distribusi Berjalan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Distribusi Berjalan</span>
                <span class="text-2xl font-black text-slate-900">{{ $distribusiBerjalan ?? 1 }}</span>
                <span class="text-[11px] text-slate-500 block mt-0.5">Armada di dalam perjalanan</span>
            </div>
        </div>

        <!-- Stok Logistik Kritis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Stok Logistik Kritis</span>
                <span class="text-2xl font-black text-slate-900">{{ $stokKritis ?? 4 }}</span>
                <span class="text-[11px] text-slate-500 block mt-0.5">Perlu pengajuan ke BPBD</span>
            </div>
        </div>

        <!-- Posko Kecil Terdaftar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Posko Kecil Terdaftar</span>
                <span class="text-2xl font-black text-slate-900">{{ $totalPoskoKecil ?? 5 }}</span>
                <span class="text-[11px] text-slate-500 block mt-0.5">Titik aktif di bawah komando</span>
            </div>
        </div>
    </div>

    <!-- PINTASAN MENU UTAMA -->
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-slate-900">Pintasan Menu Utama</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Data Logistik -->
            <a href="{{ route('komando.logistik.index') }}" class="p-5 bg-white rounded-2xl border border-slate-200/80 hover:border-orange-500 shadow-xs hover:shadow-md transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-orange-500 transition">Data Logistik</h4>
                        <p class="text-[11px] text-slate-500">Tinjau & putuskan pengajuan dari Posko Kecil</p>
                    </div>
                </div>
                <div class="p-1.5 rounded-full bg-slate-100 text-slate-400 group-hover:bg-orange-500 group-hover:text-white transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- Distribusi Logistik -->
            <a href="{{ route('komando.distribusi.index') }}" class="p-5 bg-white rounded-2xl border border-slate-200/80 hover:border-orange-500 shadow-xs hover:shadow-md transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-orange-500 transition">Distribusi Logistik</h4>
                        <p class="text-[11px] text-slate-500">Atur armada & rute pengiriman ke Posko Kecil</p>
                    </div>
                </div>
                <div class="p-1.5 rounded-full bg-slate-100 text-slate-400 group-hover:bg-orange-500 group-hover:text-white transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- Pengajuan Kebutuhan -->
            <a href="#" class="p-5 bg-white rounded-2xl border border-slate-200/80 hover:border-orange-500 shadow-xs hover:shadow-md transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-orange-500 transition">Pengajuan Kebutuhan</h4>
                        <p class="text-[11px] text-slate-500">Ajukan tambahan stok ke BPBD saat kebutuhan belum tercukupi</p>
                    </div>
                </div>
                <div class="p-1.5 rounded-full bg-slate-100 text-slate-400 group-hover:bg-orange-500 group-hover:text-white transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- Pendataan Pos Kecil -->
            <a href="#" class="p-5 bg-white rounded-2xl border border-slate-200/80 hover:border-orange-500 shadow-xs hover:shadow-md transition flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-orange-500 transition">Pendataan Pos Kecil</h4>
                        <p class="text-[11px] text-slate-500">Daftarkan titik Posko Kecil baru & buat kode undangan</p>
                    </div>
                </div>
                <div class="p-1.5 rounded-full bg-slate-100 text-slate-400 group-hover:bg-orange-500 group-hover:text-white transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

        </div>
    </div>

    <!-- AKTIVITAS & LOG TERBARU -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900">Aktivitas & Log Terbaru</h3>
            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            <!-- Log 1 -->
            <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></div>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-900">Distribusi Selesai</h5>
                        <p class="text-[11px] text-slate-500">Armada B 9021 XYZ telah menyelesaikan distribusi ke Posko Kecil Suli.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 self-end sm:self-auto">
                    <span class="text-[11px] text-slate-400 font-medium">10 menit yang lalu</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Selesai</span>
                </div>
            </div>

            <!-- Log 2 -->
            <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></div>
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-900">Pengajuan Baru</h5>
                        <p class="text-[11px] text-slate-500">Posko Kecil Walenrang mengajukan kebutuhan logistik untuk 200 jiwa pengungsi.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 self-end sm:self-auto">
                    <span class="text-[11px] text-slate-400 font-medium">35 menit yang lalu</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Menunggu</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection