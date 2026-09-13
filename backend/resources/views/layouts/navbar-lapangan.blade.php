<nav class="w-full bg-blue-800 shadow-md sticky top-0 z-50 text-white" x-data="{
    isOnline: navigator.onLine,
    init() {
        window.addEventListener('online', () => this.isOnline = true);
        window.addEventListener('offline', () => this.isOnline = false);
    }
}">
    <div class="w-full px-6 lg:px-10">
        <div class="flex justify-between h-16">

            <div class="flex items-center space-x-3 cursor-text select-none">
                <div
                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center p-1 shadow-xs border border-blue-200 shrink-0">
                    <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo Projek" class="h-8 w-8 object-contain">
                </div>

                <span class="font-bold text-white text-lg tracking-wide hidden sm:inline-block">RESCUE-LOG</span>
            </div>

            <div class="flex items-center space-x-4" x-data="{ open: false }">

                <div class="flex items-center">
                    <template x-if="isOnline">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-100 border border-emerald-300/30 transition-all">
                            <span class="w-2 h-2 mr-2 bg-emerald-300 rounded-full animate-pulse"></span>
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                            <span class="hidden md:inline">Online</span>
                        </span>
                    </template>

                    <template x-if="!isOnline">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-100 border border-rose-300/30 transition-all">
                            <span class="w-2 h-2 mr-2 bg-rose-300 rounded-full"></span>
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.657a9 9 0 010-12.728m0 0l2.829 2.829M1 1l22 22" />
                            </svg>
                            <span>Offline (Mode Lokal)</span>
                        </span>
                    </template>
                </div>

                <div class="relative">
                    <button @click="open = !open" type="button"
                        class="flex items-center space-x-3 focus:outline-none py-1 px-2.5 cursor-pointer">

                        <div
                            class="w-9 h-9 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center text-gray-600 shrink-0">
                            <x-heroicon-s-user class="w-5 h-5" />
                        </div>

                        <div class="text-left hidden md:block">
                            <p class="text-sm font-bold text-white leading-tight">
                                {{ auth()->user()->name ?? 'Petugas Lapangan' }}
                            </p>
                            <p class="text-xs text-blue-100 capitalize mt-0.5">
                                {{ str_replace('_', ' ', auth()->user()->role ?? 'Petugas Lapangan') }}
                            </p>
                        </div>

                        <x-heroicon-o-chevron-down
                            class="w-4 h-4 text-blue-200 transition-transform duration-200 stroke-[2.5]"
                            ::class="{ 'rotate-180': open }" />
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
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
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 flex items-center space-x-2 transition cursor-pointer">
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
