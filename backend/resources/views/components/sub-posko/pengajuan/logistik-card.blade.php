@props([
    'name',
    'label',
    'unit',
    'step' => '1',
    'iconBg' => 'bg-blue-100/70',
    'iconColor' => 'text-blue-600',
    'value' => 0,
    'aiValue' => 0,
    'icon'
])

<div class="bg-white rounded-xl border border-slate-300 p-7 shadow-xs hover:border-blue-500 transition flex flex-col justify-between gap-4">
    
    <!-- Bagian Atas: Icon, Nama, dan Badge AI -->
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-2xl {{ $iconBg }} {{ $iconColor }} flex items-center justify-center shrink-0">
                {!! $icon !!}
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $label }}</h4>
                <span class="inline-flex items-center gap-1 text-[11px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-md mt-1 border border-blue-100/60">
                    Rekomendasi AI: {{ $aiValue }} {{ $unit }}
                </span>
            </div>
        </div>

        <!-- Ikon Kecil di Kanan Atas Card -->
        <div class="text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
    </div>

    <!-- Bagian Bawah: Kontrol Tombol Minus, Input Angka, Tombol Plus, dan Satuan -->
    <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
        <div class="flex items-center gap-1.5 w-full">
            <!-- Tombol Kurang (-) Warna Biru -->
            <button type="button" onclick="updateQty('{{ $name }}', -{{ $step }})" class="w-9 h-9 flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold transition shadow-xs cursor-pointer shrink-0">
                -
            </button>

            <!-- Input Angka (Panah Bawaan Dihilangkan) -->
            <input type="number" 
                   id="input-{{ $name }}" 
                   name="{{ $name }}" 
                   value="{{ $value }}" 
                   step="{{ $step }}" 
                   min="0" 
                   class="w-full text-center font-bold text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

            <!-- Tombol Tambah (+) Warna Biru -->
            <button type="button" onclick="updateQty('{{ $name }}', {{ $step }})" class="w-9 h-9 flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold transition shadow-xs cursor-pointer shrink-0">
                +
            </button>
        </div>

        <!-- Label Satuan di Kanan Bawah -->
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 w-14 text-right">
            {{ $unit }}
        </span>
    </div>

</div>