<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Total Item Jenis"
            :value="$totalJenis ?? 15"
            subtitle="Jenis kebutuhan logistik"
            iconBg="bg-blue-50/80"
            iconColor="text-blue-600"
            type="users"
        />
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Perkiraan Penerima Manfaat"
            :value="$totalPenerimaManfaat ?? 658"
            subtitle="Jiwa (berdasarkan data pengungsi)"
            iconBg="bg-purple-500/10"
            iconColor="text-purple-600"
            type="user-group"
        />
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Status Rekomendasi AI"
            subtitle="Berdasarkan data terbaru · 04 Sep 2026"
            iconBg="bg-emerald-500/10"
            iconColor="text-emerald-600"
            :isBadge="true"
            badgeText="Aktif"
            badgeColor="bg-emerald-500 text-white"
            type="cpu-chip"
        />
    </div>

</div>