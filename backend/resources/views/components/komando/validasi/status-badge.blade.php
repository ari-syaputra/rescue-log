@props(['status'])

@php
    $statusLower = strtolower($status);
@endphp

@if($statusLower == 'pending')
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-50 text-amber-700 rounded-lg border border-amber-200/60">
        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
        Menunggu Validasi
    </span>
@elseif($statusLower == 'disetujui')
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200/60">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        Disetujui
    </span>
@elseif($statusLower == 'selesai')
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-teal-50 text-teal-700 rounded-lg border border-teal-200/60">
        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
        Diterima Sub-Posko
    </span>
@elseif($statusLower == 'ditolak')
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-rose-50 text-rose-700 rounded-lg border border-rose-200/60">
        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
        Ditolak
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
        {{ ucfirst($status) }}
    </span>
@endif