<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-pink-600 text-white rounded-xl shadow-xs">
                <x-heroicon-s-heart class="w-5 h-5" />
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Higiene dan Kesehatan</h3>
                <p class="text-xs text-slate-500">Kebersihan dan kesehatan untuk menjaga kondisi pengungsi</p>
            </div>
        </div>

        <span class="inline-flex items-center text-xs text-blue-600 font-bold bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 w-fit">
            4 item
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'hygiene_kit_paket',
            'label' => 'Hygiene Kit',
            'unit' => 'PAKET',
            'iconBg' => 'bg-indigo-100/70',
            'iconColor' => 'text-indigo-600',
            'value' => round($estimasi['hygiene_kit_paket'] ?? 18),
            'aiValue' => round($estimasi['hygiene_kit_paket'] ?? 18),
            'icon' => 'sparkles'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'selimut_pcs',
            'label' => 'Selimut',
            'unit' => 'PCS',
            'iconBg' => 'bg-blue-100/70',
            'iconColor' => 'text-blue-600',
            'value' => round($estimasi['selimut_pcs'] ?? 62),
            'aiValue' => round($estimasi['selimut_pcs'] ?? 62),
            'icon' => 'home'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'matras_terpal_pcs',
            'label' => 'Matras / Tempat Tidur',
            'unit' => 'PCS',
            'iconBg' => 'bg-lime-100/70',
            'iconColor' => 'text-lime-600',
            'value' => round($estimasi['matras_terpal_pcs'] ?? 18),
            'aiValue' => round($estimasi['matras_terpal_pcs'] ?? 18),
            'icon' => 'squares-2x2'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'obat_p3k_paket',
            'label' => 'Obat P3K',
            'unit' => 'PAKET',
            'iconBg' => 'bg-orange-100/70',
            'iconColor' => 'text-orange-600',
            'value' => round($estimasi['obat_p3k_paket'] ?? 3),
            'aiValue' => round($estimasi['obat_p3k_paket'] ?? 3),
            'icon' => 'plus-circle'
        ])

    </div>
</div>