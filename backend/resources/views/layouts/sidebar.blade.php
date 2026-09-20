@php
    $role = auth()->user()->role ?? '';
@endphp

@if (!in_array($role, ['lapangan', 'sub_posko', 'petugas_lapangan']))
    <aside id="main-sidebar" x-data="{
        sidebarOpen: $persist(true).as('rescue_log_sidebar_state')
    }" :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="relative bg-blue-800 text-white flex flex-col h-screen shrink-0 shadow-xl transition-all duration-300 z-40 overflow-visible">

        <!-- Brand Header -->
        <div class="p-4 flex items-center justify-between border-b border-blue-700/50 min-h-[72px]">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>

                <div x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap overflow-hidden">
                    <h2 class="font-black text-sm tracking-wider leading-tight text-white uppercase">
                        @if (in_array($role, ['bnpb', 'pusat']))
                            BNPB PUSAT
                        @elseif(in_array($role, ['bpbd_provinsi', 'provinsi']))
                            BPBD PROVINSI
                        @elseif (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                            BPBD KAB/KOTA
                        @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))
                            POSKO KOMANDO
                        @endif
                    </h2>
                    <p class="text-[10px] text-blue-200/80 font-semibold tracking-widest uppercase mt-0.5">RESCUE-LOG SYSTEM</p>
                </div>
            </div>

            <button @click.stop="sidebarOpen = !sidebarOpen" type="button"
                class="absolute -right-3.5 top-6 z-50 bg-blue-700 hover:bg-blue-600 text-white p-1 rounded-full shadow-md border-2 border-white transition-all active:scale-95 cursor-pointer flex items-center justify-center focus:outline-none">
                <x-heroicon-o-chevron-left x-show="sidebarOpen" class="w-4 h-4" />
                <x-heroicon-o-chevron-right x-show="!sidebarOpen" class="w-4 h-4" />
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="p-4 flex-1 overflow-y-auto space-y-1.5 overflow-x-hidden">
            <nav class="space-y-1.5">

                {{-- ================= 1. BNPB PUSAT ================= --}}
                @if (in_array($role, ['bnpb', 'pusat']))
                    <a href="{{ Route::has('bnpb.dashboard') ? route('bnpb.dashboard') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('bnpb.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">National Command</span>
                    </a>
                    <a href="{{ Route::has('bnpb.monitoring') ? route('bnpb.monitoring') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('bnpb.monitoring*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-globe-alt class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Monitoring Bencana</span>
                    </a>

                {{-- ================= 2. BPBD PROVINSI ================= --}}
                @elseif (in_array($role, ['bpbd_provinsi', 'provinsi']))
                    <a href="{{ Route::has('provinsi.dashboard') ? route('provinsi.dashboard') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('provinsi.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard Provinsi</span>
                    </a>
                    <a href="{{ Route::has('provinsi.eskalasi.index') ? route('provinsi.eskalasi.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('provinsi.eskalasi*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-arrow-up-tray class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Eskalasi Kab/Kota</span>
                    </a>

                {{-- ================= 3. BPBD KAB/KOTA ================= --}}
                @elseif (in_array($role, ['admin', 'bpbd', 'bpbd_kabkota']))
                    <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                    </a>
                    <a href="{{ Route::has('admin.bencana') ? route('admin.bencana') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.bencana*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-exclamation-triangle class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Inisiasi Bencana</span>
                    </a>
                    <a href="{{ Route::has('admin.posko.create') ? route('admin.posko.create') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.posko.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-building-office-2 class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Aktivasi Posko Komando</span>
                    </a>
                    <a href="{{ Route::has('admin.inventaris') ? route('admin.inventaris') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.inventaris*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-archive-box class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Stok Gudang Utama</span>
                    </a>
                    <a href="{{ Route::has('admin.distribusi.index') ? route('admin.distribusi.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.distribusi*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-truck class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Distribusi & Eskalasi</span>
                    </a>

                {{-- ================= 4. POSKO KOMANDO ================= --}}
                @elseif(in_array($role, ['komando', 'koordinator_komando', 'posko_komando']))
                    <a href="{{ Route::has('komando.dashboard') ? route('komando.dashboard') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.dashboard') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                    </a>
                    <a href="{{ Route::has('komando.posko-kecil.index') ? route('komando.posko-kecil.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.posko-kecil.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-map-pin class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Pendataan Sub-Posko</span>
                    </a>
                    <a href="{{ Route::has('komando.logistik.index') ? route('komando.logistik.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.logistik.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-archive-box class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Stok Logistik</span>
                    </a>
                    <a href="{{ Route::has('komando.validasi.index') ? route('komando.validasi.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.validasi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-clipboard-document-check class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Validasi Logistik</span>
                    </a>
                    <a href="{{ Route::has('komando.pengajuan.index') ? route('komando.pengajuan.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.pengajuan.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-paper-airplane class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Eskalasi Logistik</span>
                    </a>
                    <a href="{{ Route::has('komando.distribusi.index') ? route('komando.distribusi.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.distribusi.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-truck class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Distribusi</span>
                    </a>
                    <a href="{{ Route::has('komando.sos.index') ? route('komando.sos.index') : '#' }}"
                        class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('komando.sos.*') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                        <x-heroicon-s-heart class="w-5 h-5 shrink-0" />
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Ambulans</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- User Profile & Logout Bottom Footer -->
        <div class="p-3 border-t border-blue-700/50 bg-blue-900/30 relative" x-data="{ userMenuOpen: false }">
            <button @click.stop="if (sidebarOpen) userMenuOpen = !userMenuOpen" type="button"
                class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-white/10 transition-colors text-left focus:outline-none cursor-pointer">
                <div class="w-9 h-9 rounded-full bg-blue-700 border border-blue-500/50 flex items-center justify-center shrink-0 text-white">
                    <x-heroicon-s-user class="w-5 h-5" />
                </div>

                <div x-show="sidebarOpen" x-transition.opacity class="flex-1 min-w-0 overflow-hidden">
                    <p class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-blue-200/80 capitalize truncate mt-0.5">
                        {{ str_replace('_', ' ', auth()->user()->role ?? 'Role') }}
                    </p>
                </div>

                <x-heroicon-o-chevron-down x-show="sidebarOpen"
                    class="w-4 h-4 text-blue-200 shrink-0 transition-transform duration-200"
                    x-bind:class="userMenuOpen ? 'rotate-180' : ''" />
            </button>

            <div x-show="sidebarOpen && userMenuOpen" @click.outside="userMenuOpen = false" x-cloak
                class="absolute bottom-16 left-3 right-3 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50 text-slate-800">
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2.5 text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50/80 rounded-lg transition-all duration-150 cursor-pointer group">
                        <x-heroicon-o-arrow-left-on-rectangle
                            class="w-4 h-4 text-rose-500 group-hover:text-rose-600 shrink-0 transition-transform group-hover:-translate-x-0.5" />
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </div>

    </aside>
@endif