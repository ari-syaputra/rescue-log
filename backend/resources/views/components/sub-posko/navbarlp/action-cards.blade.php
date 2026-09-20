@php
    $cards = [
        [
            'route'   => route('lapangan.pengungsi.index'),
            'title'   => 'Pendataan Pengungsi',
            'desc'    => 'Input & update KK / khusus',
            'icon'    => 'heroicon-s-users',
            'bg_icon' => 'bg-purple-50 text-purple-600',
            'text'    => 'text-purple-700',
            'arrow'   => 'text-purple-600',
        ],
        [
            'route'   => route('lapangan.pengajuan.index'),
            'title'   => 'Pengajuan Logistik',
            'desc'    => 'Ajukan kebutuhan posko',
            'icon'    => 'heroicon-s-inbox-stack',
            'bg_icon' => 'bg-blue-50 text-blue-600',
            'text'    => 'text-blue-700',
            'arrow'   => 'text-blue-600',
        ],
        [
            'route'   => route('lapangan.stok.index'),
            'title'   => 'Status Distribusi',
            'desc'    => 'Cek status stok & pengajuan',
            'icon'    => 'heroicon-s-truck',
            'bg_icon' => 'bg-emerald-50 text-emerald-600',
            'text'    => 'text-emerald-700',
            'arrow'   => 'text-emerald-600',
        ],
        [
            'route'   => route('lapangan.penyaluran.index'),
            'title'   => 'Pengiriman & BAST',
            'desc'    => 'Catat penyaluran & stok',
            'icon'    => 'heroicon-s-clipboard-document-check',
            'bg_icon' => 'bg-amber-50 text-amber-600',
            'text'    => 'text-amber-700',
            'arrow'   => 'text-amber-600',
        ],
        [
            'route'   => route('lapangan.ambulans.index'),
            'title'   => 'Request Ambulan',
            'desc'    => 'Permintaan darurat medis',
            'icon'    => 'heroicon-s-phone',
            'bg_icon' => 'bg-rose-50 text-rose-600',
            'text'    => 'text-rose-700',
            'arrow'   => 'text-rose-600',
            'full_mobile' => true, // Kartu ke-5 melebar penuh jika ganjil di HP
        ],
    ];
@endphp

<!-- GRID 2 KOLOM DI HP (grid-cols-2), 3 KOLOM DI TABLET, 5 KOLOM DI DESKTOP -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6 font-sans antialiased w-full">
    @foreach ($cards as $card)
        <a href="{{ $card['route'] }}"
            @class([
                'bg-white hover:bg-blue-50/40 border border-gray-100 hover:border-blue-400 shadow-sm hover:shadow-md p-3.5 sm:p-4 pt-4 sm:pt-5 rounded-2xl transition-all duration-200 flex flex-col items-center justify-between text-center group cursor-pointer',
                'col-span-2 md:col-span-1 lg:col-span-1' => isset($card['full_mobile']) && $card['full_mobile'],
                'col-span-1' => !isset($card['full_mobile']) || !$card['full_mobile'],
            ])>
            
            <!-- Konten Utama -->
            <div class="flex flex-col items-center text-center w-full">
                <!-- Kotak Ikon -->
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl {{ $card['bg_icon'] }} flex items-center justify-center mb-2 sm:mb-2.5 transition-transform group-hover:scale-105 shadow-2xs">
                    <x-dynamic-component :component="$card['icon']" class="w-5 h-5 sm:w-7 sm:h-7" />
                </div>

                <!-- Judul Utama -->
                <h4 class="font-bold text-gray-900 text-xs sm:text-[15px] tracking-tight leading-snug transition-colors line-clamp-2">
                    {{ $card['title'] }}
                </h4>

                <!-- Deskripsi Singkat -->
                <p class="text-[10px] sm:text-xs text-gray-400 font-normal mt-1 leading-tight sm:leading-relaxed line-clamp-2">
                    {{ $card['desc'] }}
                </p>
            </div>

            <!-- Ikon Panah Pojok Kanan Bawah -->
            <div class="w-full flex justify-end mt-2">
                <x-heroicon-o-chevron-right class="w-3.5 h-3.5 sm:w-4 sm:h-4 {{ $card['arrow'] }} group-hover:translate-x-1 transition-transform" />
            </div>
        </a>
    @endforeach
</div>