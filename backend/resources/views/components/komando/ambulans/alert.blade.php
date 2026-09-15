@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 p-4 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
            <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-xl leading-none cursor-pointer">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="bg-rose-50 border border-rose-200/80 text-rose-800 p-4 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-3">
            <x-heroicon-s-x-circle class="w-5 h-5 text-rose-600 shrink-0" />
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-xl leading-none cursor-pointer">&times;</button>
    </div>
@endif