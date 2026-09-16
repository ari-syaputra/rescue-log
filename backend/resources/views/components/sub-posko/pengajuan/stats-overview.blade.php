@props([
    'pendataan' => null,
    'estimasi' => [],
    'totalPenerimaManfaat' => null
])

@php
    // 1. Total Jenis Logistik Eksplisit
    $totalJenis = 12;

    // 2. Hitung Total Penerima Manfaat secara Fleksibel (Cek property DB & Fallback penjumlahan)
    if ($totalPenerimaManfaat !== null && $totalPenerimaManfaat > 0) {
        $jiwa = $totalPenerimaManfaat;
    } elseif ($pendataan) {
        $jiwa = $pendataan->total_pengungsi 
            ?? $pendataan->jumlah_pengungsi 
            ?? (($pendataan->balita ?? 0) + ($pendataan->anak ?? 0) + ($pendataan->dewasa ?? 0) + ($pendataan->lansia ?? 0));
    } else {
        $jiwa = 0;
    }

    // 3. Status Rekomendasi AI & Tanggal Pembaruan
    $isAiActive = !empty($estimasi) && count($estimasi) > 0;
    $statusText = $isAiActive ? 'Otomatis (AI)' : 'Manual';
    $statusColor = $isAiActive ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white';
    
    $tanggalUpdate = $pendataan && $pendataan->updated_at 
        ? $pendataan->updated_at->translatedFormat('d M Y') 
        : now()->translatedFormat('d M Y');
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- Card 1: Total Item Jenis -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Total Item Jenis"
            :value="$totalJenis"
            subtitle="Jenis kebutuhan logistik"
            iconBg="bg-blue-50/80"
            iconColor="text-blue-600"
            type="users"
        />
    </div>

    <!-- Card 2: Total Penerima Manfaat (Otomatis dari DB) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Perkiraan Penerima Manfaat"
            :value="number_format($jiwa, 0, ',', '.') . ' Jiwa'"
            subtitle="Berdasarkan data pengungsi terkini"
            iconBg="bg-purple-500/10"
            iconColor="text-purple-600"
            type="user-group"
        />
    </div>

    <!-- Card 3: Status Rekomendasi AI (Otomatis dari Respon FastAPI) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 md:p-6 flex items-center gap-4">
        <x-sub-posko.pengajuan.stats-card
            title="Status Rekomendasi AI"
            subtitle="Berdasarkan data {{ $tanggalUpdate }}"
            iconBg="bg-emerald-500/10"
            iconColor="text-emerald-600"
            :isBadge="true"
            :badgeText="$statusText"
            :badgeColor="$statusColor"
            type="cpu-chip"
        />
    </div>

</div>