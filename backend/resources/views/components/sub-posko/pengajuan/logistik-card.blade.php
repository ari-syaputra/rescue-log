@props([
    'name',
    'label',
    'unit',
    'step' => '1',
    'iconBg' => 'bg-blue-100/70',
    'iconColor' => 'text-blue-600',
    'value' => 0,
    'aiValue' => 0,
    'icon' => 'cube'
])

<div class="bg-white rounded-xl border border-slate-300 p-5 md:p-6 shadow-xs hover:border-blue-500 transition flex flex-col justify-between gap-4">
    
    <!-- Bagian Atas: Icon, Nama, dan Badge AI -->
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-2xl {{ $iconBg }} {{ $iconColor }} flex items-center justify-center shrink-0">
                <!-- Pengecekan Toleran: Bisa String Heroicon maupun SVG Mentah -->
                @if(str_contains($icon, '<svg'))
                    {!! $icon !!}
                @else
                    <x-dynamic-component :component="'heroicon-s-' . ($icon ?: 'cube')" class="w-6 h-6 shrink-0" />
                @endif
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $label }}</h4>
                <span class="inline-flex items-center gap-1 text-[11px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-md mt-1 border border-blue-100/60">
                    Rekomendasi AI: {{ $aiValue }} {{ $unit }}
                </span>
            </div>
        </div>

        <!-- Ikon Kubus Kanan Atas -->
        <div class="text-slate-400 shrink-0">
            <x-heroicon-s-cube class="w-5 h-5" />
        </div>
    </div>

    <!-- Bagian Bawah: Kontrol Tombol Minus, Input Angka, Tombol Plus, dan Satuan -->
    <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
        <div class="flex items-center gap-1.5 w-full">
            <!-- Tombol Kurang (-) -->
            <button type="button" 
                    onclick="updateQty('{{ $name }}', -{{ $step }})" 
                    class="w-9 h-9 flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold transition shadow-xs cursor-pointer shrink-0">
                -
            </button>

            <!-- Input Angka -->
            <input type="number" 
                   id="input-{{ $name }}" 
                   name="{{ $name }}" 
                   value="{{ old($name, $value) }}" 
                   step="{{ $step }}" 
                   min="0" 
                   class="w-full text-center font-bold text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

            <!-- Tombol Tambah (+) -->
            <button type="button" 
                    onclick="updateQty('{{ $name }}', {{ $step }})" 
                    class="w-9 h-9 flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold transition shadow-xs cursor-pointer shrink-0">
                +
            </button>
        </div>

        <!-- Label Satuan -->
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 w-12 text-right">
            {{ $unit }}
        </span>
    </div>

</div>