@props([
    'bencana' => null,
    'totalPengungsi' => null
])

@php
    // Path ke Gambar Hero Banner Ilustrasi Kartun
    $heroImage = asset('img/hero-posko-kecil.png');
@endphp

<div class="relative w-full rounded-2xl overflow-hidden shadow-sm border border-slate-200/80 bg-slate-900 mb-6 flex items-center min-h-60 md:min-h-70"
     x-data="{ activeSlide: 1 }">
    
    <!-- 1. Background Image Ilustrasi Posko Lapangan -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ $heroImage }}');">
    </div>

    </div>
</div>