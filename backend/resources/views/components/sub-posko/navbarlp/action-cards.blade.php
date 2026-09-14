@php
    $cards = [
        [
            'route'   => route('lapangan.pengungsi.index'),
            'title'   => 'Pendataan Pengungsi',
            'desc'    => 'Input dan update data KK dan kategori khusus',
            'icon'    => 'heroicon-s-users',
            'bg_icon' => 'bg-purple-50 text-purple-600',
            'text'    => 'text-purple-700',
            'arrow'   => 'text-purple-600',
            'full_md' => false,
        ],
        [
            'route'   => route('lapangan.pengajuan.index'),
            'title'   => 'Pengajuan Logistik',
            'desc'    => 'Ajukan kebutuhan logistik ke Posko Komando',
            'icon'    => 'heroicon-s-inbox-stack',
            'bg_icon' => 'bg-blue-50 text-blue-600',
            'text'    => 'text-blue-700',
            'arrow'   => 'text-blue-600',
            'full_md' => false,
        ],
        [
            'route'   => route('lapangan.stok.index'),
            'title'   => 'Status Distribusi',
            'desc'    => 'Lihat status pengajuan dan stok logistik',
            'icon'    => 'heroicon-s-truck',
            'bg_icon' => 'bg-emerald-50 text-emerald-600',
            'text'    => 'text-emerald-700',
            'arrow'   => 'text-emerald-600',
            'full_md' => false,
        ],
        [
            'route'   => route('lapangan.penyaluran.index'),
            'title'   => 'Pengiriman dan BAST',
            'desc'    => 'Catat penyaluran dan kelola stok',
            'icon'    => 'heroicon-s-clipboard-document-check',
            'bg_icon' => 'bg-amber-50 text-amber-600',
            'text'    => 'text-amber-700',
            'arrow'   => 'text-amber-600',
            'full_md' => true,
        ],
        [
            'route'   => route('lapangan.ambulans.index'),
            'title'   => 'Request Ambulan',
            'desc'    => 'Permintaan darurat armada ambulan',
            'icon'    => 'fas-truck-medical',
            'bg_icon' => 'bg-rose-50 text-rose-600',
            'text'    => 'text-rose-700',
            'arrow'   => 'text-rose-600',
            'full_md' => true,
        ],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 lg:grid-cols-5 gap-4 mb-6 font-sans antialiased">
    @foreach ($cards as $card)
        <a href="{{ $card['route'] }}"
            @class([
                'bg-white hover:bg-blue-50/40 border border-gray-100 hover:border-blue-400 shadow-md shadow-gray-200/60 hover:shadow-blue-100 p-4 pt-5 rounded-2xl transition-all duration-200 flex flex-col items-center justify-between text-center group',
                'md:col-span-2 lg:col-span-1' => !$card['full_md'],
                'md:col-span-3 lg:col-span-1' => $card['full_md'],
            ])>
            
            <!-- Konten Utama -->
            <div class="flex flex-col items-center text-center w-full">
                <!-- Kotak Ikon -->
                <div class="w-14 h-14 rounded-2xl {{ $card['bg_icon'] }} flex items-center justify-center mb-2.5 transition-transform group-hover:scale-105 shadow-sm">
                    <x-dynamic-component :component="$card['icon']" class="w-7 h-7" />
                </div>

                <!-- Judul Utama -->
                <h4 class="font-bold text-gray-900 text-[16px] tracking-tight leading-snug group-hover:{{ $card['text'] }} transition-colors">
                    {{ $card['title'] }}
                </h4>

                <!-- Deskripsi -->
                <p class="text-xs text-gray-500 font-normal mt-1 leading-relaxed tracking-normal">
                    {{ $card['desc'] }}
                </p>
            </div>

            <!-- Ikon Panah Pojok Kanan Bawah -->
            <div class="w-full flex justify-end mt-2">
                <x-heroicon-o-chevron-right class="w-4 h-4 {{ $card['arrow'] }} group-hover:translate-x-1 transition-transform" />
            </div>
        </a>
    @endforeach
</div>