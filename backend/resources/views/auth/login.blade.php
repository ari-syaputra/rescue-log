@extends('layouts.guest')

@section('title', 'Login - Sistem Penanganan Bencana BPBD')

@section('content')
    <div class="min-h-screen w-full flex flex-col md:flex-row">

        <!-- SISI KIRI: Hero Section -->
        <div class="hidden md:flex md:w-1/2 lg:w-7/12 relative bg-slate-900 justify-between flex-col p-12 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-center"
                style="background-image: url('{{ asset('img/login.png.png') }}');">
            </div>
            <div class="absolute inset-0 bg-linear-to-br from-blue-950/90 via-slate-900/95 to-slate-950/90 z-0"></div>

            <!-- Header Brand -->
            <div class="relative z-10 flex items-center space-x-1.5 bg-transparent">
                <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo BPBD"
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
                            <p class="text-slate-400 text-sm">Koordinasi terstruktur antara Posko Komando dan Sub Posko Lapangan.</p>
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

        <!-- SISI KANAN: Form Login -->
        <div class="w-full md:w-1/2 lg:w-5/12 bg-slate-50 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">

                <!-- Header Form -->
                <div class="text-center mb-6">
                    <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo Rescue Log" class="w-16 h-16 object-contain mx-auto mb-3">
                    <h3 class="text-2xl font-bold text-slate-900">Selamat Datang</h3>
                    <p class="text-slate-500 text-sm mt-1">Silakan pilih jenis akses masuk RESCUE-LOG</p>
                </div>

                <!-- TAB SELECTOR TYPE LOGIN -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <!-- Tab Posko Komando -->
                    <button type="button" id="tab-komando" onclick="switchLoginMode('komando')"
                        class="p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-amber-500 border-amber-600 text-white shadow-md">
                        <div id="icon-komando-wrapper" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1.5">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <span class="block text-xs font-bold">Posko Komando</span>
                        <span id="sub-komando-text" class="block text-[10px] opacity-90 mt-0.5">Email & Password</span>
                    </button>

                    <!-- Tab Sub Posko -->
                    <button type="button" id="tab-subposko" onclick="switchLoginMode('subposko')"
                        class="p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100">
                        <div id="icon-subposko-wrapper" class="w-8 h-8 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1.5">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                        </div>
                        <span class="block text-xs font-bold">Sub Posko</span>
                        <span id="sub-subposko-text" class="block text-[10px] text-slate-500 mt-0.5">Kode Akses / Undangan</span>
                    </button>
                </div>

                <!-- Alert Errors Login -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-xl flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- SECTION 1: LOGIN POSKO KOMANDO (Email & Password) -->
                    <div id="section-komando" class="space-y-4">
                        <!-- Input Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Username / Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                                    placeholder="Masukkan email petugas">
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="key" class="w-5 h-5"></i>
                                </div>
                                <input type="password" name="password" id="password"
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
                                    placeholder="Masukkan password">
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Pass -->
                        <div class="flex items-center justify-between text-sm pt-1">
                            <label class="flex items-center text-slate-600 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                                <span class="ml-2 text-xs font-medium">Ingat saya</span>
                            </label>
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lupa password?</a>
                        </div>
                    </div>

                    <!-- SECTION 2: LOGIN SUB POSKO (1 Kolom Kode Undangan) -->
                    <div id="section-subposko" class="hidden space-y-4">
                        <div>
                            <label for="kode_undangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Kode Undangan / Akses Sub Posko</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                                    <i data-lucide="ticket" class="w-5 h-5"></i>
                                </div>
                                <input type="text" name="kode_undangan" id="kode_undangan" value="{{ old('kode_undangan') }}"
                                    class="w-full pl-11 pr-4 py-3 bg-emerald-50/50 border border-emerald-200 uppercase tracking-widest text-emerald-900 font-mono font-bold text-base rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder:text-slate-400 placeholder:font-normal placeholder:tracking-normal placeholder:text-sm"
                                    placeholder="Contoh: PSK-A8F2K9">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-2 flex items-center gap-1">
                                <i data-lucide="info" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                Masukkan kode resmi yang didapatkan dari Posko Komando.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" id="btn-submit"
                        class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl shadow-lg shadow-amber-600/30 flex items-center justify-center space-x-2 transition duration-200">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span id="btn-text">Masuk Posko Komando</span>
                    </button>
                </form>

            </div>
        </div>

     
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Script Switch Mode Login -->
        <script>
            function switchLoginMode(mode) {
                const tabKomando = document.getElementById('tab-komando');
                const tabSubposko = document.getElementById('tab-subposko');
                
                const iconKomandoWrapper = document.getElementById('icon-komando-wrapper');
                const iconSubposkoWrapper = document.getElementById('icon-subposko-wrapper');

                const sectionKomando = document.getElementById('section-komando');
                const sectionSubposko = document.getElementById('section-subposko');

                const inputEmail = document.getElementById('email');
                const inputPassword = document.getElementById('password');
                const inputKode = document.getElementById('kode_undangan');

                const btnSubmit = document.getElementById('btn-submit');
                const btnText = document.getElementById('btn-text');

                if (mode === 'komando') {
                    // Active style for Komando
                    tabKomando.className = "p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-amber-500 border-amber-600 text-white shadow-md";
                    iconKomandoWrapper.className = "w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1.5";
                    
                    // Inactive style for Sub Posko
                    tabSubposko.className = "p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100";
                    iconSubposkoWrapper.className = "w-8 h-8 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1.5";

                    // Show/Hide Sections
                    sectionKomando.classList.remove('hidden');
                    sectionSubposko.classList.add('hidden');

                    // Inputs validation switching
                    inputEmail.required = true;
                    inputPassword.required = true;
                    inputKode.required = false;
                    inputKode.value = ''; // clear input

                    // Submit Button Style
                    btnSubmit.className = "w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl shadow-lg shadow-amber-600/30 flex items-center justify-center space-x-2 transition duration-200";
                    btnText.innerText = "Masuk Posko Komando";

                } else {
                    // Active style for Sub Posko
                    tabSubposko.className = "p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-blue-600 border-blue-700 text-white shadow-md";
                    iconSubposkoWrapper.className = "w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1.5";

                    // Inactive style for Komando
                    tabKomando.className = "p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100";
                    iconKomandoWrapper.className = "w-8 h-8 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1.5";

                    // Show/Hide Sections
                    sectionKomando.classList.add('hidden');
                    sectionSubposko.classList.remove('hidden');

                    // Inputs validation switching
                    inputEmail.required = false;
                    inputPassword.required = false;
                    inputKode.required = true;
                    inputEmail.value = '';
                    inputPassword.value = '';

                    // Submit Button Style
                    btnSubmit.className = "w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/30 flex items-center justify-center space-x-2 transition duration-200";
                    btnText.innerText = "Masuk Sub Posko";
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Lucide icons if available
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Restore active state if validation returned error on kode_undangan
                @if(old('kode_undangan'))
                    switchLoginMode('subposko');
                @else
                    switchLoginMode('komando');
                @endif

                // Toast SweetAlert
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