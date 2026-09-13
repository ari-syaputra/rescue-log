<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Kebutuhan Dasar</h3>
                <p class="text-xs text-slate-500">Kebutuhan konsumsi harian dan perlengkapan dasar pengungsi</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'beras_kg',
            'label' => 'Beras',
            'unit' => 'KG',
            'step' => '0.1',
            'iconBg' => 'bg-blue-100/70',
            'iconColor' => 'text-blue-600',
            'value' => round($estimasi['beras_kg'] ?? 136.9, 1),
            'aiValue' => round($estimasi['beras_kg'] ?? 136.9, 1),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'air_minum_dus',
            'label' => 'Air Minum',
            'unit' => 'DUS',
            'iconBg' => 'bg-sky-100/70',
            'iconColor' => 'text-sky-600',
            'value' => round($estimasi['air_minum_dus'] ?? 71),
            'aiValue' => round($estimasi['air_minum_dus'] ?? 71),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14m-6 0a6 6 0 10-12 0 6 6 0 0012 0zM12 3v11"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'makanan_kaleng_pack',
            'label' => 'Makanan Kaleng',
            'unit' => 'PACK',
            'iconBg' => 'bg-emerald-100/70',
            'iconColor' => 'text-emerald-600',
            'value' => round($estimasi['makanan_kaleng_pack'] ?? 658),
            'aiValue' => round($estimasi['makanan_kaleng_pack'] ?? 658),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'makanan_bayi_pack',
            'label' => 'Makanan Bayi',
            'unit' => 'PACK',
            'iconBg' => 'bg-pink-100/70',
            'iconColor' => 'text-pink-600',
            'value' => round($estimasi['makanan_bayi_pack'] ?? 180),
            'aiValue' => round($estimasi['makanan_bayi_pack'] ?? 180),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'minyak_goreng_liter',
            'label' => 'Minyak Goreng',
            'unit' => 'LITER',
            'step' => '0.1',
            'iconBg' => 'bg-amber-100/70',
            'iconColor' => 'text-amber-600',
            'value' => round($estimasi['minyak_goreng_liter'] ?? 33.6, 1),
            'aiValue' => round($estimasi['minyak_goreng_liter'] ?? 33.6, 1),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.605 15.12a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'popok_bayi_pcs',
            'label' => 'Popok Bayi',
            'unit' => 'PCS',
            'iconBg' => 'bg-purple-100/70',
            'iconColor' => 'text-purple-600',
            'value' => round($estimasi['popok_bayi_pcs'] ?? 213),
            'aiValue' => round($estimasi['popok_bayi_pcs'] ?? 213),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'popok_dewasa_pcs',
            'label' => 'Popok Dewasa',
            'unit' => 'PCS',
            'iconBg' => 'bg-teal-100/70',
            'iconColor' => 'text-teal-600',
            'value' => round($estimasi['popok_dewasa_pcs'] ?? 85),
            'aiValue' => round($estimasi['popok_dewasa_pcs'] ?? 85),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'pembalut_wanita_pack',
            'label' => 'Pembalut Wanita',
            'unit' => 'PACK',
            'iconBg' => 'bg-rose-100/70',
            'iconColor' => 'text-rose-600',
            'value' => round($estimasi['pembalut_wanita_pack'] ?? 9),
            'aiValue' => round($estimasi['pembalut_wanita_pack'] ?? 9),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>'
        ])

    </div>
</div>