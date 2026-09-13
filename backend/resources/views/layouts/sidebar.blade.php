@php
    $role = auth()->user()->role ?? '';
@endphp

<aside id="main-sidebar" :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-[#1b2250] text-white flex flex-col min-h-screen shrink-0 shadow-xl transition-all duration-300 z-40 overflow-hidden">

    <!-- Brand Header -->
    <div class="p-5 flex items-center gap-3 border-b border-indigo-900/40">
        <div class="w-10 h-10 flex-shrink-0">
            <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        </div>

        <div x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap overflow-hidden">
            <h2 class="font-black text-base tracking-wider leading-tight text-white uppercase">
                @if (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                    BPBD KAB/KOTA
                @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))
                    POSKO KOMANDO
                @else
                    SUB-POSKO LAPANGAN
                @endif
            </h2>
            <p class="text-[11px] text-indigo-300/60 font-semibold tracking-widest uppercase mt-0.5">RESCUE-LOG SYSTEM</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="p-4 flex-1 overflow-y-auto">
        <nav class="space-y-1.5">

            {{-- ================= 1. BPBD KAB/KOTA ================= --}}
            @if (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                
                {{-- Dashboard Admin --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Inisiasi Bencana --}}
                <a href="{{ route('admin.bencana') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.bencana*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-house-crack class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Inisiasi Bencana</span>
                </a>

                {{-- Aktivasi Posko Komando --}}
                <a href="{{ route('admin.posko.create') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.posko.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-building-office-2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Aktivasi Posko Komando</span>
                </a>

                {{-- Stok Gudang Utama --}}
                <a href="{{ route('admin.inventaris') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.inventaris*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-boxes-stacked class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Stok Gudang Utama</span>
                </a>

                {{-- Distribusi & Eskalasi --}}
                <a href="{{ route('admin.distribusi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.distribusi*') || request()->routeIs('admin.permintaan*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-truck class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Distribusi & Eskalasi</span>
                </a>

                {{-- Laporan --}}
                <a href="{{ route('admin.laporan') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.laporan*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-chart-line class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Laporan</span>
                </a>

            {{-- ================= 2. POSKO KOMANDO ================= --}}
            @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))

                {{-- Dashboard Komando --}}
                <a href="{{ route('komando.dashboard') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Pendataan Sub-Posko --}}
                <a href="{{ route('komando.posko-kecil.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.posko-kecil.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-map-pin class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Pendataan Sub-Posko</span>
                </a>

                {{-- Stok Logistik Komando --}}
                <a href="{{ route('komando.logistik.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.logistik.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-boxes-stacked class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Stok Logistik Komando</span>
                </a>

                {{-- Validasi Logistik Sub-Posko (Routing ke Route Validasi Baru) --}}
                <a href="{{ route('komando.validasi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.validasi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-clipboard-check class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Validasi Logistik</span>
                </a>

                {{-- Eskalasi Logistik (Permohonan Komando ke BPBD) --}}
                <a href="{{ route('komando.pengajuan.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.pengajuan.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-paper-plane class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Eskalasi Logistik ke BPBD</span>
                </a>

                {{-- Distribusi & Fleet Routing --}}
                <a href="{{ route('komando.distribusi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.distribusi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-truck class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Distribusi & Fleet Routing</span>
                </a>

                {{-- Dispatch Medis (Ambulans) --}}
                <a href="{{ Route::has('komando.sos.index') ? route('komando.sos.index') : '#' }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.sos.*') ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-truck-medical class="w-5 h-5 shrink-0 text-red-400 group-hover:text-white" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Ambulans</span>
                </a>

            {{-- ================= 3. SUB-POSKO (POSKO LAPANGAN) ================= --}}
            @else

                {{-- Dashboard Lapangan --}}
                <a href="{{ route('lapangan.dashboard') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('lapangan.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Pendataan Pengungsi (Agregat) --}}
                <a href="{{ route('lapangan.pengungsi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('lapangan.pengungsi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-users class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Pendataan Pengungsi (Agregat)</span>
                </a>

                {{-- Pengajuan Logistik --}}
                <a href="{{ route('lapangan.pengajuan.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('lapangan.pengajuan.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-clipboard-list class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Pengajuan Logistik</span>
                </a>

                {{-- Status Pengiriman & Konfirmasi BAST --}}
                <a href="{{ route('lapangan.stok.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('lapangan.stok.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-file-contract class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Status Pengiriman & BAST</span>
                </a>

                {{-- Penyaluran Logistik (Ke Pengungsi) --}}
                <a href="{{ route('lapangan.penyaluran.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('lapangan.penyaluran.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-hand-holding-heart class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Penyaluran ke Pengungsi</span>
                </a>

                {{-- Request Ambulans --}}
                <a href="#"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 text-indigo-200/70 hover:bg-white/10 hover:text-white">
                    <x-fas-truck-medical class="w-5 h-5 shrink-0 text-red-400" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Request Ambulans</span>
                </a>

            @endif
        </nav>
    </div>
</aside>