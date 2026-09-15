{{-- <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-30 shrink-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none transition-colors cursor-pointer"
            aria-label="Toggle Sidebar">
            <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" type="button" class="flex items-center gap-3 focus:outline-none cursor-pointer">
            
            <div class="w-9 h-9 rounded-full bg-gray-300 border border-gray-300 flex items-center justify-center shadow-sm">
                <x-heroicon-s-user class="w-5 h-5 text-gray-700" />
            </div>
            
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-gray-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-[11px] text-gray-500 capitalize">
                    {{ str_replace('_', ' ', auth()->user()->role ?? 'Role') }}
                </p>
            </div>
            <x-heroicon-o-chevron-down class="w-4 h-4 text-black transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" />
        </button>

        <div x-show="open" @click.outside="open = false" x-cloak
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
            <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 cursor-pointer">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</header> --}}