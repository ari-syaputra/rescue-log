@extends('layouts.guest')

@section('title', 'Login - Sistem Penanganan Bencana BPBD')

@section('content')
    <div class="min-h-screen w-full flex flex-col md:flex-row">

        <!-- SISI KIRI: Hero Section -->
        <div class="hidden md:flex md:w-1/2 lg:w-6/12 xl:w-7/12 relative bg-slate-900 justify-between flex-col p-12 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-center"
                style="background-image: url('{{ asset('img/login.png.png') }}');">
            </div>
            <div class="absolute inset-0 bg-linear-to-br from-blue-950/90 via-slate-900/95 to-slate-950/90 z-0"></div>

            <!-- Header Brand -->
            <div class="relative z-10 flex items-center space-x-1.5 bg-transparent">
                <img src="{{ asset('img/rescue-log.png') }}" alt="Logo BPBD"
                    class="w-[100px] h-[120px] object-contain flex-shrink-0 bg-transparent">

                <div class="bg-transparent">
                    <h2 class="text-white font-bold text-lg tracking-wider leading-none">BPBD</h2>
                    <p class="text-amber-400 text-xs font-semibold tracking-widest mt-0.5">RESCUE-LOG</p>
                </div>
            </div>

            <!-- Body Hero -->
            <div class="relative z-10 my-auto py-12 max-w-xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight mb-4">
                    Sistem Penanganan Bencana
                </h1>
                <p class="text-slate-300 text-base leading-relaxed mb-10">
                    Kelola informasi bencana, posko pengungsian, stok logistik, dan distribusi bantuan secara terintegrasi
                    dan real-time.
                </p>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-base">Cepat</h4>
                            <p class="text-slate-400 text-sm">Respon cepat dalam penanganan darurat bencana.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-base">Terkoordinasi</h4>
                            <p class="text-slate-400 text-sm">Koordinasi terstruktur antara Posko Komando dan Posko Lapangan.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10">
                            <i data-lucide="box" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-base">Terintegrasi</h4>
                            <p class="text-slate-400 text-sm">Data pengungsi dan penyaluran logistik terkelola dengan akurat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Hero -->
            <div class="relative z-10 text-xs text-slate-400 border-t border-white/10 pt-4">
                &copy; 2026 BPBD - RESCUE-LOG System
            </div>
        </div>

        <!-- SISI KANAN: Form Login (Lebih Luas & Proporsional) -->
        <div class="w-full md:w-1/2 lg:w-6/12 xl:w-5/12 bg-slate-50 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-lg bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100">

                <!-- Header Form -->
                <div class="text-center mb-8">
                    <img src="{{ asset('img/rescue-log.png') }}" alt="Logo Rescue Log" class="w-20 h-20 object-contain mx-auto mb-3">
                    <h3 class="text-3xl font-extrabold text-slate-900">Selamat Datang</h3>
                    <p class="text-slate-500 text-sm mt-1.5">Silakan masuk untuk mengakses RESCUE-LOG</p>
                </div>

                <!-- Alert Errors Login -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-xl flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-base focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                                placeholder="Masukkan email petugas">
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="key-round" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-base focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                                placeholder="Masukkan password">
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Pass -->
                    <div class="flex items-center justify-between text-sm py-1">
                        <label class="flex items-center text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                            <span class="ml-2 text-xs font-semibold">Ingat saya</span>
                        </label>
                        <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lupa password?</a>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full py-3.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-base rounded-xl shadow-lg shadow-blue-700/30 flex items-center justify-center space-x-2 transition duration-200 cursor-pointer">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <span>Masuk</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <span class="relative bg-white px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Akses Role Sistem</span>
                </div>

                <!-- Shortcut Card Role BPBD -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-amber-50/60 border border-amber-200/80 rounded-xl text-center">
                        <div class="w-9 h-9 bg-amber-500 text-white rounded-lg flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <span class="block text-xs font-bold text-amber-900">Posko Komando</span>
                        <span class="block text-[11px] text-amber-700 mt-0.5">Akses Induk BPBD</span>
                    </div>

                    <div class="p-4 bg-emerald-50/60 border border-emerald-200/80 rounded-xl text-center">
                        <div class="w-9 h-9 bg-emerald-600 text-white rounded-lg flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <i data-lucide="tent" class="w-5 h-5"></i>
                        </div>
                        <span class="block text-xs font-bold text-emerald-900">Posko Kecil</span>
                        <span class="block text-[11px] text-emerald-700 mt-0.5">Akses Lapangan</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- SweetAlert2 Notifikasi -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                @if (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @endif

                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                @endif
            });
        </script>

    </div>
@endsection