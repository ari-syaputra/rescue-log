<div class="space-y-6 w-full animate-pulse">
    <!-- Skeleton Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @for ($i = 0; $i < 4; $i++)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-xl"></div>
                    <div class="space-y-2 flex-1">
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Skeleton Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div class="h-6 bg-gray-200 rounded w-1/4"></div>
            <div class="h-4 bg-gray-200 rounded w-1/6"></div>
        </div>
        <div class="space-y-3 pt-2">
            @for ($i = 0; $i < 5; $i++)
                <div class="h-10 bg-gray-100 rounded-lg w-full"></div>
            @endfor
        </div>
    </div>
</div>