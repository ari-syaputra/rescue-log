@props([
    'bencana' => null,
    'totalPengungsi' => null
])

@php
    $heroImage = asset('img/hero-posko-kecil.png');
@endphp

<div class="relative w-full rounded-2xl md:rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 bg-slate-900 mb-6 flex items-center min-h-36 md:min-h-44 px-6 md:px-8 py-4 justify-between"
     x-data="{ activeSlide: 1 }">

    <!-- 1. Gambar Ilustrasi (Pakai object-bottom agar bagian bawah/karakter tetap terlihat) -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ $heroImage }}" 
             alt="Hero Posko" 
             class="w-full h-full object-cover object-bottom md:object-right-bottom">
    </div>

    <!-- 2. Konten Teks -->
    <div class="relative z-10 max-w-xs sm:max-w-md">
    </div>
    
</div>