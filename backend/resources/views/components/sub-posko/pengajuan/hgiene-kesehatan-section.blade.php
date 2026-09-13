<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-pink-600 text-white rounded-xl shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
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
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.605 15.12a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'selimut_pcs',
            'label' => 'Selimut',
            'unit' => 'PCS',
            'iconBg' => 'bg-blue-100/70',
            'iconColor' => 'text-blue-600',
            'value' => round($estimasi['selimut_pcs'] ?? 62),
            'aiValue' => round($estimasi['selimut_pcs'] ?? 62),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'matras_terpal_pcs',
            'label' => 'Matras / Tempat Tidur',
            'unit' => 'PCS',
            'iconBg' => 'bg-lime-100/70',
            'iconColor' => 'text-lime-600',
            'value' => round($estimasi['matras_terpal_pcs'] ?? 18),
            'aiValue' => round($estimasi['matras_terpal_pcs'] ?? 18),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>'
        ])

        @include('components.sub-posko.pengajuan.logistik-card', [
            'name' => 'obat_p3k_paket',
            'label' => 'Obat P3K',
            'unit' => 'PAKET',
            'iconBg' => 'bg-orange-100/70',
            'iconColor' => 'text-orange-600',
            'value' => round($estimasi['obat_p3k_paket'] ?? 3),
            'aiValue' => round($estimasi['obat_p3k_paket'] ?? 3),
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ])

    </div>
</div>