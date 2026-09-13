@php
    $role = auth()->user()->role ?? '';
@endphp

<aside id="main-sidebar" :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-[#1b2250] text-white flex flex-col min-h-screen shrink-0 shadow-xl transition-all duration-300 z-40 overflow-hidden">

    <div class="p-5 flex items-center gap-3 border-b border-indigo-900/40">
        <div class="w-10 h-10 flex-shrink-0">
            <img src="{{ asset('img/rescue-log.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        </div>

        <div x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap overflow-hidden">
            <h2 class="font-black text-base tracking-wider leading-tight text-white uppercase">
                @if (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                    BPBD KAB/KOTA
                @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))
                    POSKO KOMANDO
                @else
                    POSKO LAPANGAN
                @endif
            </h2>
            <p class="text-[11px] text-indigo-300/60 font-semibold tracking-widest uppercase mt-0.5">RESCUE-LOG SYSTEM</p>
        </div>
    </div>

    <div class="p-4 flex-1 overflow-y-auto">
        <nav class="space-y-1.5">

            {{-- ================= MENU 1: BPBD KABUPATEN / KOTA ================= --}}
            @if (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                
                {{-- Dashboard Operasional --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Inisiasi & Kaji Cepat (BMKG + TRC GIS) --}}
                <a href="{{ route('admin.bencana') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.bencana*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-house-crack class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Inisiasi & Kaji Cepat</span>
                </a>

                {{-- TAMBAHAN BARU: Aktivasi Posko Komando (Langkah 2) --}}
                <a href="{{ route('admin.posko.create') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.posko.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-building-office-2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Aktivasi Posko Komando</span>
                </a>

                {{-- Stok Gudang Utama & Cold-Start ML --}}
                <a href="{{ route('admin.inventaris') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.inventaris*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-boxes-stacked class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Gudang Utama & ML</span>
                </a>

                {{-- Permintaan Eskalasi Restock (ke Prov/BNPB) --}}
                <a href="{{ route('admin.permintaan') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.permintaan*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-clipboard-list class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Eskalasi Restock</span>
                </a>

                {{-- Monitoring Fleet & Distribusi Regional --}}
                <a href="{{ route('admin.distribusi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.distribusi*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-truck class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Distribusi Logistik</span>
                </a>

                {{-- Laporan & Audit Trail --}}
                <a href="{{ route('admin.laporan') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.laporan*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-chart-line class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Laporan & Audit</span>
                </a>

            {{-- ================= MENU 2: POSKO KOMANDO ================= --}}
            @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))

                {{-- Dashboard Taktis & Live GIS --}}
                <a href="{{ route('komando.dashboard') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard Taktis GIS</span>
                </a>

                {{-- Registrasi & Plotting Sub-Posko --}}
                <a href="{{ route('komando.posko-kecil.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.posko-kecil.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-heroicon-s-map-pin class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Registrasi Sub-Posko</span>
                </a>

                {{-- Respons SOS Medis (Triase) --}}
                <a href="{{ Route::has('komando.sos.index') ? route('komando.sos.index') : '#' }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.sos.*') ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-kit-medical class="w-5 h-5 shrink-0 text-red-400 group-hover:text-white" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">SOS Alert Medis</span>
                </a>

                {{-- Approval Logistik (Anti-Bullwhip) --}}
                <a href="{{ route('komando.pengajuan.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.pengajuan.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-clipboard-list class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Approval Logistik ML</span>
                </a>

                {{-- AI Dynamic Rerouting & Fleet Tracking --}}
                <a href="{{ route('komando.distribusi.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.distribusi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-truck class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dynamic Rerouting GIS</span>
                </a>

                {{-- Inventaris & Buffer Stok Komando --}}
                <a href="{{ route('komando.logistik.index') }}"
                    class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.logistik.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-indigo-200/70 hover:bg-white/10 hover:text-white' }}">
                    <x-fas-boxes-stacked class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Buffer Stok Komando</span>
                </a>
            @endif
        </nav>
    </div>
</aside>