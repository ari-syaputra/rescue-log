<nav class="w-full bg-blue-800 shadow-md sticky top-0 z-50 text-white" x-data="{
    isOnline: navigator.onLine,
    init() {
        window.addEventListener('online', () => { this.isOnline = true; });
        window.addEventListener('offline', () => { this.isOnline = false; });
    }
}">
    <div class="w-full px-4 sm:px-6 lg:px-10">
        <div class="flex justify-between h-16">

            <!-- KIRI: Logo & Brand -->
            <div class="flex items-center space-x-3 cursor-text select-none">
                <div class="w-10 h-10 rounded-xl bg-white/60 border border-white/20 backdrop-blur-md p-1.5 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo RESCUE-LOG" class="h-full w-full object-contain">
                </div>

                <div class="hidden sm:flex flex-col">
                    <span class="font-black text-white text-lg tracking-wider uppercase leading-none">
                        RESCUE-LOG
                    </span>
                    <span class="text-[10px] font-semibold tracking-widest text-blue-200/80 uppercase mt-1 leading-none">
                        Sistem Posko Kebencanaan
                    </span>
                </div>
            </div>

            <!-- KANAN: Tombol Install PWA, Indikator Sinyal & Profile Dropdown -->
            <div class="flex items-center space-x-2 sm:space-x-4" x-data="{ open: false }">

                <!-- TOMBOL INSTALL PWA (OTOMATIS MUNCUL JIKA DAPAT DI-INSTALL) -->
                <button id="btn-install-pwa" type="button" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-400 text-slate-900 font-bold text-xs shadow-md hover:bg-amber-300 transition cursor-pointer">
                    <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Install App</span>
                </button>

                <!-- INDIKATOR STATUS KONEKSI (HIJAU = ONLINE, MERAH = OFFLINE) -->
                <div class="flex items-center">
                    <!-- Saat Online: Ikon Wifi Hijau -->
                    <template x-if="isOnline">
                        <span class="inline-flex items-center justify-center p-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 transition-all duration-300" title="Sinyal Online">
                            <x-heroicon-o-wifi class="w-4 h-4 text-emerald-300" />
                        </span>
                    </template>

                    <!-- Saat Offline: Ikon Wifi Dicoret Merah + Bouncing Animation -->
                    <template x-if="!isOnline">
                        <span class="inline-flex items-center justify-center p-2 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-400/30 transition-all duration-300 animate-pulse" title="Terputus (Offline Mode)">
                            <svg class="w-4 h-4 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M12 20h.01m-4.08-3.596a5.5 5.5 0 014.07-1.404m3.708 1.404a5.5 5.5 0 00.37-.371m-7.08-3.125a10.026 10.026 0 013.012-1.312m6.128 1.312a9.96 9.96 0 011.892 1.813M1.394 9.393a15.94 15.94 0 014.243-2.923m11.314 0a15.94 15.94 0 014.243 2.923" />
                            </svg>
                        </span>
                    </template>
                </div>

                <!-- USER PROFILE DROPDOWN -->
                <div class="relative">
                    <button @click="open = !open" type="button" class="flex items-center space-x-2 sm:space-x-3 focus:outline-none py-1 px-1.5 sm:px-2.5 cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                            <x-heroicon-s-user class="w-5 h-5" />
                        </div>

                        <div class="text-left hidden md:block">
                            <p class="text-sm font-bold text-white leading-tight">
                                {{ auth()->user()->name ?? 'Petugas Lapangan' }}
                            </p>
                            <p class="text-xs text-blue-200 capitalize mt-0.5">
                                {{ str_replace('_', ' ', auth()->user()->role ?? 'Petugas Lapangan') }}
                            </p>
                        </div>

                        <x-heroicon-o-chevron-down class="w-4 h-4 text-blue-200 transition-transform duration-200 stroke-[2.5]" ::class="{ 'rotate-180': open }" />
                    </button>

                    <!-- DROPDOWN MENU -->
                    <div x-show="open" @click.away="open = false" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl py-1 border border-slate-100 z-50 text-slate-800"
                        style="display: none;">

                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-sm font-bold text-slate-800 truncate">
                                {{ auth()->user()->name ?? 'Petugas Lapangan' }}
                            </p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">
                                {{ auth()->user()->email ?? '' }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 flex items-center space-x-2 transition cursor-pointer">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>