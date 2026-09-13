<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="h-28 bg-slate-300 animate-pulse rounded-2xl"></div>
        <div class="h-28 bg-slate-300 animate-pulse rounded-2xl"></div>
        <div class="h-28 bg-slate-300 animate-pulse rounded-2xl"></div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-300 animate-pulse rounded-xl"></div>
            <div class="space-y-2">
                <div class="w-36 h-5 bg-slate-300 animate-pulse rounded-md"></div>
                <div class="w-64 h-3.5 bg-slate-200 animate-pulse rounded-md"></div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @for ($i = 0; $i < 8; $i++)
                @include('components.skeleton.item-card')
            @endfor
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-300 animate-pulse rounded-xl"></div>
                <div class="space-y-2">
                    <div class="w-40 h-5 bg-slate-300 animate-pulse rounded-md"></div>
                    <div class="w-52 h-3.5 bg-slate-200 animate-pulse rounded-md"></div>
                </div>
            </div>
            <div class="w-16 h-6 bg-slate-300 animate-pulse rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @for ($i = 0; $i < 4; $i++)
                @include('components.skeleton.item-card')
            @endfor
        </div>
    </div>

    <div class="h-32 bg-slate-200 animate-pulse rounded-2xl"></div>

    <div class="flex justify-end">
        <div class="w-full sm:w-40 h-12 bg-slate-200 animate-pulse rounded-xl"></div>
    </div>
</div>