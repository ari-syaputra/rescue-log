<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-xs">
                <x-heroicon-s-shopping-cart class="w-5 h-5" />
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
            'icon' => 'shopping-bag'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'air_minum_dus',
            'label' => 'Air Minum',
            'unit' => 'DUS',
            'iconBg' => 'bg-sky-100/70',
            'iconColor' => 'text-sky-600',
            'value' => round($estimasi['air_minum_dus'] ?? 71),
            'aiValue' => round($estimasi['air_minum_dus'] ?? 71),
            'icon' => 'beaker'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'makanan_kaleng_pack',
            'label' => 'Makanan Kaleng',
            'unit' => 'PACK',
            'iconBg' => 'bg-emerald-100/70',
            'iconColor' => 'text-emerald-600',
            'value' => round($estimasi['makanan_kaleng_pack'] ?? 658),
            'aiValue' => round($estimasi['makanan_kaleng_pack'] ?? 658),
            'icon' => 'archive-box'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'makanan_bayi_pack',
            'label' => 'Makanan Bayi',
            'unit' => 'PACK',
            'iconBg' => 'bg-pink-100/70',
            'iconColor' => 'text-pink-600',
            'value' => round($estimasi['makanan_bayi_pack'] ?? 180),
            'aiValue' => round($estimasi['makanan_bayi_pack'] ?? 180),
            'icon' => 'face-smile'
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
            'icon' => 'sparkles'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'popok_bayi_pcs',
            'label' => 'Popok Bayi',
            'unit' => 'PCS',
            'iconBg' => 'bg-purple-100/70',
            'iconColor' => 'text-purple-600',
            'value' => round($estimasi['popok_bayi_pcs'] ?? 213),
            'aiValue' => round($estimasi['popok_bayi_pcs'] ?? 213),
            'icon' => 'user-group'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'popok_dewasa_pcs',
            'label' => 'Popok Dewasa',
            'unit' => 'PCS',
            'iconBg' => 'bg-teal-100/70',
            'iconColor' => 'text-teal-600',
            'value' => round($estimasi['popok_dewasa_pcs'] ?? 85),
            'aiValue' => round($estimasi['popok_dewasa_pcs'] ?? 85),
            'icon' => 'user'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'pembalut_wanita_pack',
            'label' => 'Pembalut Wanita',
            'unit' => 'PACK',
            'iconBg' => 'bg-rose-100/70',
            'iconColor' => 'text-rose-600',
            'value' => round($estimasi['pembalut_wanita_pack'] ?? 9),
            'aiValue' => round($estimasi['pembalut_wanita_pack'] ?? 9),
            'icon' => 'heart'
        ])

    </div>
</div>