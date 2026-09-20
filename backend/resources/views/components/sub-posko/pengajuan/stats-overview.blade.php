@props([
    'pendataan' => null,
    'estimasi' => [],
    'totalPenerimaManfaat' => null
])

@php
    // 1. Total Jenis Logistik Eksplisit
    $totalJenis = 12;

    // 2. Hitung Total Penerima Manfaat secara Fleksibel
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
    $statusBg = $isAiActive ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200';
    
    $tanggalUpdate = $pendataan && $pendataan->updated_at 
        ? $pendataan->updated_at->translatedFormat('d M Y') 
        : now()->translatedFormat('d M Y');
@endphp

<!-- GRID 3-KOLOM HORIZONTAL DI MOBILE (grid-cols-3) -->
<div class="grid grid-cols-3 gap-2 sm:gap-4 mb-6 font-sans">

    <!-- Card 1: Total Item Jenis -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
            <div class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <x-heroicon-s-inbox-stack class="w-4 h-4 sm:w-6 sm:h-6" />
            </div>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate">Total Item</span>
        </div>
        <div>
            <div class="text-base sm:text-2xl font-black text-slate-900 leading-none">
                {{ $totalJenis }}
            </div>
            <span class="text-[9px] sm:text-xs text-slate-400 font-medium block mt-1 truncate">
                Jenis logistik
            </span>
        </div>
    </div>

    <!-- Card 2: Total Penerima Manfaat -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
            <div class="p-1.5 sm:p-2 bg-purple-50 text-purple-600 rounded-xl shrink-0">
                <x-heroicon-s-users class="w-4 h-4 sm:w-6 sm:h-6" />
            </div>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate">Penerima</span>
        </div>
        <div>
            <div class="text-base sm:text-2xl font-black text-slate-900 leading-none">
                {{ number_format($jiwa, 0, ',', '.') }}
                <span class="text-[9px] sm:text-xs font-bold text-purple-600">Jiwa</span>
            </div>
            <span class="text-[9px] sm:text-xs text-slate-400 font-medium block mt-1 truncate">
                Data pengungsi
            </span>
        </div>
    </div>

    <!-- Card 3: Status Rekomendasi AI -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
            <div class="p-1.5 sm:p-2 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                <x-heroicon-s-cpu-chip class="w-4 h-4 sm:w-6 sm:h-6" />
            </div>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate">Status AI</span>
        </div>
        <div>
            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded-md text-[9px] sm:text-xs font-bold border {{ $statusBg }} truncate">
                {{ $statusText }}
            </span>
            <span class="text-[8px] sm:text-[10px] text-slate-400 font-medium block mt-1 truncate">
                Data {{ $tanggalUpdate }}
            </span>
        </div>
    </div>

</div>