@props(['bpbd' => null])

@php
    // Ambil data BPBD dari prop controller atau fallback ke relasi auth user
    $namaBpbd = $bpbd->nama_bpbd 
        ?? ($bpbd['nama_bpbd'] 
        ?? (auth()->user()->bpbd->nama_bpbd 
        ?? (auth()->user()->nama_posko 
        ?? 'BPBD Kabupaten/Kota')));

    $namaUser = auth()->user()->name ?? 'Administrator';
@endphp

<!-- Container Banner (Height 52 & Padding seimbang) -->
<div class="relative overflow-hidden rounded-2xl h-52 px-6 py-5 text-white shadow-sm bg-cover bg-[center_top_15%] flex flex-col justify-between"
    style="background-image: url('{{ asset('img/bpbd1.png') }}');">
    
    <!-- Overlay Gradient Dark Blend -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-900/50 to-transparent"></div>

    <!-- 1. Teks Selamat Datang & Penjelasan di Paling Atas -->
    <div class="relative z-10">
        <h1 class="text-2xl md:text-3xl font-black tracking-tight drop-shadow-md flex items-center gap-2">
            <span>Selamat Datang, {{ $namaUser }}</span>
        </h1>
        <p class="text-xs md:text-sm text-slate-100 font-medium mt-1 drop-shadow-md flex items-center gap-1.5">
            <x-heroicon-s-building-office-2 class="w-4 h-4 text-orange-400 shrink-0" />
            <span>{{ $namaBpbd }} — Sistem Informasi Penanggulangan Bencana</span>
        </p>
    </div>

    <!-- 2. Badge Jam & Tanggal di Tengah-Tengah Banner -->
    <div class="relative z-10 my-auto pt-1">
        <div x-data="liveClock()" x-init="startClock()" class="inline-flex items-center gap-2 bg-slate-950/60 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs border border-white/20 shadow-sm">
            <x-heroicon-o-calendar class="w-4 h-4 text-orange-400 shrink-0" />
            <span class="font-semibold text-white" x-text="tanggalText">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
            
            <span class="opacity-40">|</span>
            
            <x-heroicon-o-clock class="w-4 h-4 text-blue-400 shrink-0" />
            <span class="font-semibold text-white tracking-wide" x-text="jamText">
                {{ \Carbon\Carbon::now()->format('H:i:s') }} WIB
            </span>
        </div>
    </div>
</div>

<!-- Script Live Clock (Real-time) -->
<script>
    function liveClock() {
        return {
            tanggalText: '{{ \Carbon\Carbon::now()->locale("id")->isoFormat("dddd, D MMMM Y") }}',
            jamText: '{{ \Carbon\Carbon::now()->format("H:i:s") }} WIB',
            startClock() {
                setInterval(() => {
                    const now = new Date();
                    
                    // Format Tanggal Indonesia
                    const optionsTanggal = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    this.tanggalText = now.toLocaleDateString('id-ID', optionsTanggal);
                    
                    // Format Jam 24 Jam dengan Detik
                    const jam = String(now.getHours()).padStart(2, '0');
                    const menit = String(now.getMinutes()).padStart(2, '0');
                    const detik = String(now.getSeconds()).padStart(2, '0');
                    this.jamText = `${jam}:${menit}:${detik} WIB`;
                }, 1000);
            }
        }
    }
</script>